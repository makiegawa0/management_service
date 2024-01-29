<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Carbon\CarbonPeriod;
use Exception;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $users;

    /**
     * @var CampaignTenantRepositoryInterface
     */
    // protected $campaigns;

    /**
     * @var MessageTenantRepositoryInterface
     */
    // protected $messages;

    /**
     * @var CampaignStatisticsService
     */
    // protected $campaignStatisticsService;

    public function __construct(UserRepository $users)
    {        
        $this->users = $users;
        // $this->campaigns = $campaigns;
        // $this->messages = $messages;
        // $this->campaignStatisticsService = $campaignStatisticsService;
    }

    /**
     * @throws Exception
     */
    public function index(): View
    {
        // $workspaceId = Sendportal::currentWorkspaceId();
        // $completedCampaigns = $this->campaigns->completedCampaigns($workspaceId, ['status']);
        $userGrowthChart = $this->getUserGrowthChart();

        return view('dashboard.index', [
            // 'recentSubscribers' => $this->subscribers->getRecentSubscribers($workspaceId),
            // 'completedCampaigns' => $completedCampaigns,
            // 'campaignStats' => $this->campaignStatisticsService->getForCollection($completedCampaigns, $workspaceId),
            'userGrowthChartLabels' => json_encode($userGrowthChart['labels']),
            'userGrowthChartData' => json_encode($userGrowthChart['data']),
        ]);
    }

    protected function getUserGrowthChart(): array
    {
        $period = CarbonPeriod::create(now()->subDays(30)->startOfDay(), now()->endOfDay());

        $growthChartData = $this->users->getGrowthChartData($period);

        $growthChart = [
            'labels' => [],
            'data' => [],
        ];

        $currentTotal = $growthChartData['startingValue'];

        foreach ($period as $date) {
            $formattedDate = $date->format('d-m-Y');

            $periodValue = $growthChartData['runningTotal'][$formattedDate]->total ?? 0;
            $periodUnsubscribe = $growthChartData['unsubscribers'][$formattedDate]->total ?? 0;
            $currentTotal += $periodValue - $periodUnsubscribe;

            $growthChart['labels'][] = $formattedDate;
            $growthChart['data'][] = $currentTotal;
        }

        return $growthChart;
    }
}
