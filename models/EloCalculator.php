<?php

namespace app\models;

class EloCalculator
{
    const K_FACTOR = 32;
    const DEFAULT_ELO = 1000;

    public static function calculateNewRatings($whiteElo, $blackElo, $result)
    {
        $expectedWhite = 1.0 / (1.0 + pow(10, ($blackElo - $whiteElo) / 400.0));
        $expectedBlack = 1.0 - $expectedWhite;

        switch ($result) {
            case 'white_win':
                $scoreWhite = 1.0;
                $scoreBlack = 0.0;
                break;
            case 'black_win':
                $scoreWhite = 0.0;
                $scoreBlack = 1.0;
                break;
            case 'draw':
                $scoreWhite = 0.5;
                $scoreBlack = 0.5;
                break;
            default:
                return [
                    'white_new_elo' => $whiteElo,
                    'black_new_elo' => $blackElo,
                    'white_change' => 0,
                    'black_change' => 0,
                ];
        }

        $whiteChange = round(self::K_FACTOR * ($scoreWhite - $expectedWhite));
        $blackChange = round(self::K_FACTOR * ($scoreBlack - $expectedBlack));

        $whiteNewElo = max(100, $whiteElo + $whiteChange);
        $blackNewElo = max(100, $blackElo + $blackChange);

        return [
            'white_new_elo' => $whiteNewElo,
            'black_new_elo' => $blackNewElo,
            'white_change' => $whiteChange,
            'black_change' => $blackChange,
        ];
    }
}