<?php

namespace Modules\Report\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Quiz\Entities\QuizResult;

class BC36 extends Model
{
    public static function totalQuiz1($from_date, $to_date, $type, $count = 1) {
        $query = Quiz::query();
        $query->where('status', '=', 1);
        
        if ($type) {
            $query->where('type_id', '=', $type);
        }
    
        $query->whereExists(function ($where) use ($from_date) {
            $where->select(['start_date'])
                ->from('el_quiz_part')
                ->whereColumn('quiz_id', '=', 'el_quiz.id')
                ->where('start_date', '>', date('Y-m-d H:i:s'))
                ->where('start_date', '=', function (Builder $sub) {
                    $sub->select([\DB::raw('MIN(start_date)')])
                        ->from('el_quiz_part')
                        ->whereColumn('quiz_id', '=', 'el_quiz.id');
                });
            
            if ($from_date) {
                $where->where('start_date', '>=', $from_date);
            }
        });
    
        $query->whereExists(function ($where) use ($to_date) {
            $where->select(['start_date'])
                ->from('el_quiz_part')
                ->whereColumn('quiz_id', '=', 'el_quiz.id')
                ->where('start_date', '>', date('Y-m-d H:i:s'))
                ->where('start_date', '=', function (Builder $sub) {
                    $sub->select([\DB::raw('MIN(start_date)')])
                        ->from('el_quiz_part')
                        ->whereColumn('quiz_id', '=', 'el_quiz.id');
                });
        
            if ($to_date) {
                $where->where('start_date', '<=', $to_date);
            }
        });
        
        if ($count == 1) {
            return $query->count();
        }
        
        return $query;
    }
    
    public static function totalQuiz2($from_date, $to_date, $type, $count = 1) {
        $query = Quiz::query();
        $query->where('status', '=', 1);
        
        if ($type) {
            $query->where('type_id', '=', $type);
        }
    
        $query->whereExists(function ($where) use ($from_date) {
            $where->select(['start_date'])
                ->from('el_quiz_part')
                ->whereColumn('quiz_id', '=', 'el_quiz.id')
                ->where('start_date', '<=', date('Y-m-d H:i:s'))
                ->where('end_date', '>=', date('Y-m-d H:i:s'))
                ->where('start_date', '=', function (Builder $sub) {
                    $sub->select([\DB::raw('MIN(start_date)')])
                        ->from('el_quiz_part')
                        ->whereColumn('quiz_id', '=', 'el_quiz.id');
                });
            
            if ($from_date) {
                $where->where('start_date', '>=', $from_date);
            }
        });
    
        $query->whereExists(function ($where) use ($to_date) {
            $where->select(['start_date'])
                ->from('el_quiz_part')
                ->whereColumn('quiz_id', '=', 'el_quiz.id')
                ->where('start_date', '<=', date('Y-m-d H:i:s'))
                ->where('end_date', '>=', date('Y-m-d H:i:s'))
                ->where('start_date', '=', function (Builder $sub) {
                    $sub->select([\DB::raw('MIN(start_date)')])
                        ->from('el_quiz_part')
                        ->whereColumn('quiz_id', '=', 'el_quiz.id');
                });
        
            if ($to_date) {
                $where->where('start_date', '<=', $to_date);
            }
        });
        //dd($query->toRawSql());
        if ($count == 1) {
            return $query->count();
        }
    
        return $query;
    }
    
    public static function totalQuiz3($from_date, $to_date, $type, $count = 1) {
        $query = Quiz::query();
        $query->where('status', '=', 1);
        
        if ($type) {
            $query->where('type_id', '=', $type);
        }
    
        $query->whereExists(function ($where) use ($from_date) {
            $where->select(['start_date'])
                ->from('el_quiz_part')
                ->whereColumn('quiz_id', '=', 'el_quiz.id')
                ->where('end_date', '<', date('Y-m-d H:i:s'))
                ->where('start_date', '=', function (Builder $sub) {
                    $sub->select([\DB::raw('MIN(start_date)')])
                        ->from('el_quiz_part')
                        ->whereColumn('quiz_id', '=', 'el_quiz.id');
                });
            
            if ($from_date) {
                $where->where('start_date', '>=', $from_date);
            }
        });
    
        $query->whereExists(function ($where) use ($to_date) {
            $where->select(['start_date'])
                ->from('el_quiz_part')
                ->whereColumn('quiz_id', '=', 'el_quiz.id')
                ->where('end_date', '<', date('Y-m-d H:i:s'))
                ->where('start_date', '=', function (Builder $sub) {
                    $sub->select([\DB::raw('MIN(start_date)')])
                        ->from('el_quiz_part')
                        ->whereColumn('quiz_id', '=', 'el_quiz.id');
                });
        
            if ($to_date) {
                $where->where('start_date', '<=', $to_date);
            }
        });
    
        if ($count == 1) {
            return $query->count();
        }
    
        return $query;
    }
    
    public static function totalRegister1($from_date, $to_date, $type) {
        $quizs = self::totalQuiz1($from_date, $to_date, $type, 0);
        $quizs = $quizs->pluck('id')->toArray();
        
        $query = QuizRegister::query();
        return $query->whereIn('quiz_id', $quizs)
            ->count();
    }
    
    public static function totalRegister2($from_date, $to_date, $type) {
        $quizs = self::totalQuiz2($from_date, $to_date, $type, 0);
        $quizs = $quizs->pluck('id')->toArray();
        
        $query = QuizRegister::query();
        return $query->whereIn('quiz_id', $quizs)
            ->count();
    }
    
    public static function totalRegister3($from_date, $to_date, $type) {
        $quizs = self::totalQuiz3($from_date, $to_date, $type, 0);
        $quizs = $quizs->pluck('id')->toArray();
        
        $query = QuizRegister::query();
        return $query->whereIn('quiz_id', $quizs)
            ->count();
    }
    
    public static function totalCompleted($from_date, $to_date, $type) {
        $quizs1 = self::totalQuiz2($from_date, $to_date, $type, 0);
        $quizs1 = $quizs1->pluck('id')->toArray();
    
        $quizs2 = self::totalQuiz3($from_date, $to_date, $type, 0);
        $quizs2 = $quizs2->pluck('id')->toArray();
    
        $quizs = array_merge($quizs1, $quizs2);
        
        $query = QuizResult::query();
        $query->where('result', '=', 1)
            ->whereIn('quiz_id', $quizs);
    
        return $query->count();
    }
    
    public static function totalFailed($from_date, $to_date, $type) {
        $total_completed = self::totalCompleted($from_date, $to_date, $type);
        $total_register = self::totalRegister1($from_date, $to_date, $type) + self::totalRegister2($from_date, $to_date, $type);
        
        return ($total_register - $total_completed);
    }
}
