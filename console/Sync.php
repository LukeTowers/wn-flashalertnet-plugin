<?php namespace LukeTowers\FlashAlertNet\Console;

use Feed;
use Carbon\Carbon;
use System\Models\File;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use LukeTowers\FlashAlertNet\Models\Alert;
use LukeTowers\FlashAlertNet\Models\Organization;

class Sync extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'flashalertnet:sync';

    /**
     * @var string The console command description.
     */
    protected $description = 'Check flashalert.net for new alerts';

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {
        $this->output->writeln('Checking flashalert.net for new alerts...');

        $orgs = Organization::all();

        if (!$orgs || !$orgs->count()) {
            throw new \ApplicationException("There are no FlashAlert.net organizations configured. Please setup an organization first.");
        }

        foreach ($orgs as $org) {
            $this->syncOrg($org);
        }

        $this->output->writeln('FlashAlert.net Sync Complete!');
    }

    protected function syncOrg($org)
    {
        $this->output->writeln('Syncing ' . $org->name . ' - ' . $org->api_org_id);
        $items = [];

        // Check for emergency alerts
        $emergencyAlertResponse = @file_get_contents("https://www.flashalert.net/emergency.html?id={$org->api_org_id}&Format=json");
        if (!empty($emergencyAlertResponse)) {
            $emergencyAlert = json_decode($emergencyAlertResponse);

            if ($emergencyAlert->Alert) {
                $guid = $org->api_org_id . '-emergency-' . $emergencyAlert->messageDate;
                $alert = Alert::firstOrNew(['guid' => $guid], ['guid' => $guid]);
                $alert->title = "Emergency Alert";
                $alert->content = $emergencyAlert->message;
                $alert->type = 'emergency';
                $alert->published_at = Carbon::parse($emergencyAlert->messageDate);
                $alert->save();
            }
        }

        try {
            $feed = Feed::loadRss("https://www.flashalert.net/rss.html?id={$org->api_org_id}");
        } catch (\Exception $e) {
            \Log::error("Could not load the {$org->name} FlashAlert Feed (https://www.flashalert.net/rss.html?id={$org->api_org_id}): " . $e->getMessage());
            return;
        }

        // Check for regular alerts
        foreach ($feed->item as $item) {
            $currentItem = [
                'organization_id'  => $org->id,
                'title'   => (string) $item->title,
                'content' => (string) $item->description,
                'guid'    => (string) $item->guid,
                'published_at' => Carbon::parse($item->pubDate),
            ];

            // https://stackoverflow.com/questions/43449504/how-to-retrieve-a-media-description-from-an-rss-file-in-php
            foreach ($item->children('media', true) as $media) {
                $attributes = $media->attributes();
                if (count($attributes)) {
                    $currentItem['files'][] = (string) $attributes->url;
                }
            }

            $items[] = $currentItem;
            $currentItem = [];
            unset($item);
        }

        foreach ($items as $item) {
            $alert = Alert::firstOrNew(['guid' => $item['guid']], ['guid' => $item['guid']]);

            if (!empty($item['files']) && empty($alert->featured_image)) {
                $file = (new File())->fromUrl($item['files'][0]);
                $alert->featured_image = $file;
                $file->save();
            }

            unset($item['files']);

            $alert->forceFill($item);
            $alert->save();
        }



    }

    /**
     * Get the console command arguments.
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }

    /**
     * Get the console command options.
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }
}
