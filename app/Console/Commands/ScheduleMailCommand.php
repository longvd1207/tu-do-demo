<?php

namespace App\Console\Commands;

use App\Jobs\SendConfirmMailJob;
use App\Models\Athlete;
use App\Models\Tournament;
use App\Models\TournamentCompetition;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ScheduleMailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gửi mail tự động trước một ngày';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $athletes = Athlete::query()->whereNotNull('athlete_code');
        foreach ($athletes as $athlete){
            $tournamentCompetitionIds = explode(',', $athlete['tournament_competition_id']);
            $tournament = Tournament::query()->find($athlete['id'])->toArray();
            $athlete['tournament_start_date'] = $tournament['tournament_start_date'];
            $athlete['tournament_end_date'] = $tournament['tournament_end_date'];
            foreach ($tournamentCompetitionIds as $tournamentCompetitionId){
                if($tournamentCompetitionId){
                    $tournamentCompetition = TournamentCompetition::query()->find($tournamentCompetitionId)->toArray();
                    $athlete['tournament_competitions'][] = [
                        'name' => $tournamentCompetition['name'],
                    ];
                }
            }
            dispatch(new SendConfirmMailJob($athlete));
        }
    }
}
