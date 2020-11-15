<?php

namespace Nerbiz\PrivateStats\Wordpress;

use Nerbiz\PrivateStats\PrivateStats;

class AdminPage
{
    /**
     * The PrivateStats object to build the page with
     * @var PrivateStats
     */
    protected $privateStats;

    public function __construct(PrivateStats $privateStats)
    {
        $this->privateStats = $privateStats;
    }

    /**
     * Create an admin page with statistics
     * @return void
     */
    public function create(): void
    {
        add_action('admin_menu', function() {
            add_menu_page(
                'Private Stats',
                'Private Stats',
                'edit_posts',
                'private-stats',
                [$this, 'render'],
                'dashicons-chart-line'
            );
        });
    }

    /**
     * Render the admin page content
     * @return void
     */
    public function render(): void
    {
        $privateStats = $this->privateStats;
        require dirname(__FILE__, 3) . '/includes/read-stats.php';
    }
}
