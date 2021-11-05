<?php

namespace App\Models\Categories;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Categories\CommitMentTitle
 *
 * @property int $id
 * @property int $commitment_id
 * @property int $title_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CommitMentTitle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CommitMentTitle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CommitMentTitle query()
 * @method static \Illuminate\Database\Eloquent\Builder|CommitMentTitle whereCommitmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommitMentTitle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommitMentTitle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommitMentTitle whereTitleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommitMentTitle whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CommitMentTitle extends Model
{
    protected $table='el_commitment_title';
    protected $fillable = [
        'commitment_id',
        'commit_group_id',
        'title_id',
    ];
}
