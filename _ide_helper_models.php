<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Club
 *
 * @property int $id
 * @property string $name
 * @property string|null $place
 * @property string|null $district
 * @property string|null $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Trainer> $trainers
 * @property-read int|null $trainers_count
 * @method static \Illuminate\Database\Eloquent\Builder|Club newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Club newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Club onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Club query()
 * @method static \Illuminate\Database\Eloquent\Builder|Club whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Club whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Club whereDistrict($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Club whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Club whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Club whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Club wherePlace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Club whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Club withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Club withoutTrashed()
 */
	class Club extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models{
/**
 * App\Models\Competition
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read mixed $dates
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MatchDay> $matchDays
 * @property-read int|null $match_days_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Team> $teams
 * @property-read int|null $teams_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Trainer> $trainers
 * @property-read int|null $trainers_count
 * @method static \Illuminate\Database\Eloquent\Builder|Competition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Competition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Competition onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Competition query()
 * @method static \Illuminate\Database\Eloquent\Builder|Competition whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Competition whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Competition whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Competition whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Competition whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Competition withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Competition withoutTrashed()
 */
	class Competition extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models{
/**
 * App\Models\DGResource
 *
 * @property int $id
 * @property string $category
 * @property string $name
 * @property string $type
 * @property string $url
 * @property string|null $old_hash
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|DGResource newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DGResource newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DGResource query()
 * @method static \Illuminate\Database\Eloquent\Builder|DGResource whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DGResource whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DGResource whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DGResource whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DGResource whereOldHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DGResource whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DGResource whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DGResource whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DGResource whereUrl($value)
 */
	class DGResource extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models{
/**
 * App\Models\Declaration
 *
 * @property int $id
 * @property int $match_day_id
 * @property int $jury_id
 * @property int $km
 * @property int $day_amount
 * @property string $iban
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|Declaration newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Declaration newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Declaration onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Declaration query()
 * @method static \Illuminate\Database\Eloquent\Builder|Declaration whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Declaration whereDayAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Declaration whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Declaration whereIban($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Declaration whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Declaration whereJuryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Declaration whereKm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Declaration whereMatchDayId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Declaration whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Declaration withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Declaration withoutTrashed()
 */
	class Declaration extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models{
/**
 * App\Models\Feedback
 *
 * @property int $id
 * @property int $user_id
 * @property string $feedback
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback query()
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereFeedback($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereUserId($value)
 */
	class Feedback extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Group
 *
 * @property int $id
 * @property int $nr
 * @property int $baan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read mixed $full_name
 * @property-read mixed $name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Registration> $registrations
 * @property-read int|null $registrations_count
 * @method static \Illuminate\Database\Eloquent\Builder|Group newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Group newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Group query()
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereBaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereNr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereUpdatedAt($value)
 */
	class Group extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models{
/**
 * App\Models\Gymnast
 *
 * @property int $id
 * @property string $name
 * @property string $birthdate
 * @property int $photo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read mixed $first_name
 * @property-read mixed $last_name
 * @method static \Illuminate\Database\Eloquent\Builder|Gymnast newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Gymnast newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Gymnast onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Gymnast query()
 * @method static \Illuminate\Database\Eloquent\Builder|Gymnast whereBirthdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gymnast whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gymnast whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gymnast whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gymnast whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gymnast wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gymnast whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gymnast withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Gymnast withoutTrashed()
 */
	class Gymnast extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models{
/**
 * App\Models\Jury
 *
 * @property int $id
 * @property string $name
 * @property string|null $function
 * @property string|null $email
 * @property string|null $postal
 * @property string|null $city
 * @property int|null $club_id
 * @property string|null $iban
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Club|null $club
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Declaration> $declarations
 * @property-read int|null $declarations_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Jury newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Jury newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Jury onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Jury query()
 * @method static \Illuminate\Database\Eloquent\Builder|Jury whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jury whereClubId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jury whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jury whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jury whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jury whereFunction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jury whereIban($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jury whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jury whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jury wherePostal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jury whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jury withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Jury withoutTrashed()
 */
	class Jury extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models{
/**
 * App\Models\Location
 *
 * @property int $id
 * @property string $name
 * @property string $address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read mixed $select_name
 * @method static \Illuminate\Database\Eloquent\Builder|Location newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Location newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Location onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Location query()
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Location withoutTrashed()
 */
	class Location extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models{
/**
 * App\Models\MatchDay
 *
 * @property int $id
 * @property string|null $name
 * @property int $competition_id
 * @property mixed $date
 * @property int $location_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Competition $competition
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Declaration> $declarations
 * @property-read int|null $declarations_count
 * @property-read mixed $niveaus
 * @property-read \App\Models\Location $location
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Registration> $registrations
 * @property-read int|null $registrations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Score> $scores
 * @property-read int|null $scores_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Wedstrijd> $wedstrijden
 * @property-read int|null $wedstrijden_count
 * @method static \Illuminate\Database\Eloquent\Builder|MatchDay newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MatchDay newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MatchDay onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|MatchDay query()
 * @method static \Illuminate\Database\Eloquent\Builder|MatchDay whereCompetitionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchDay whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchDay whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchDay whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchDay whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchDay whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchDay whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchDay whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchDay withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|MatchDay withoutTrashed()
 */
	class MatchDay extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models{
/**
 * App\Models\Niveau
 *
 * @property int $id
 * @property string $name
 * @property string $supplement
 * @property int|null $niveau_number
 * @property string|null $age_category
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read mixed $full_name
 * @method static \Illuminate\Database\Eloquent\Builder|Niveau newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Niveau newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Niveau query()
 * @method static \Illuminate\Database\Eloquent\Builder|Niveau whereAgeCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Niveau whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Niveau whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Niveau whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Niveau whereNiveauNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Niveau whereSupplement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Niveau whereUpdatedAt($value)
 */
	class Niveau extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models{
/**
 * App\Models\PendingChange
 *
 * @property int $id
 * @property string $model_type
 * @property int|null $model_id
 * @property string $operation
 * @property mixed $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PendingChange newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PendingChange newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PendingChange query()
 * @method static \Illuminate\Database\Eloquent\Builder|PendingChange whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PendingChange whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PendingChange whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PendingChange whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PendingChange whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PendingChange whereOperation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PendingChange whereUpdatedAt($value)
 */
	class PendingChange extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ProcessedScore
 *
 * @property int $id
 * @property int $wedstrijd_id
 * @property int $group_id
 * @property int $toestel
 * @property int $completed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Group|null $group
 * @property-read \App\Models\Wedstrijd $wedstrijd
 * @method static \Illuminate\Database\Eloquent\Builder|ProcessedScore newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProcessedScore newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProcessedScore query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProcessedScore whereCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProcessedScore whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProcessedScore whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProcessedScore whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProcessedScore whereToestel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProcessedScore whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProcessedScore whereWedstrijdId($value)
 */
	class ProcessedScore extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models{
/**
 * App\Models\Registration
 *
 * @property int $id
 * @property int $match_day_id
 * @property int $gymnast_id
 * @property int $club_id
 * @property int $niveau_id
 * @property int $startnumber
 * @property int $group_id
 * @property int|null $team_id
 * @property int $signed_off
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Club $club
 * @property-read \App\Models\Group $group
 * @property-read \App\Models\Gymnast $gymnast
 * @property-read \App\Models\Niveau $niveau
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Score> $scores
 * @property-read int|null $scores_count
 * @property-read \App\Models\Team|null $team
 * @property-read \App\Models\Wedstrijd|null $wedstrijd
 * @method static \Illuminate\Database\Eloquent\Builder|Registration newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Registration newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Registration query()
 * @method static \Illuminate\Database\Eloquent\Builder|Registration whereClubId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Registration whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Registration whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Registration whereGymnastId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Registration whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Registration whereMatchDayId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Registration whereNiveauId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Registration whereSignedOff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Registration whereStartnumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Registration whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Registration whereUpdatedAt($value)
 */
	class Registration extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models{
/**
 * App\Models\Score
 *
 * @property int $id
 * @property int $match_day_id
 * @property int $startnumber
 * @property int $toestel
 * @property float $d
 * @property float $e1
 * @property float|null $e2
 * @property float|null $e3
 * @property float $n
 * @property float $total
 * @property int $counted
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read mixed $e
 * @property-read mixed $e_score
 * @property-read mixed $registration
 * @property-read \App\Models\MatchDay $match_day
 * @method static \Illuminate\Database\Eloquent\Builder|Score newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Score newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Score query()
 * @method static \Illuminate\Database\Eloquent\Builder|Score whereCounted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Score whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Score whereD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Score whereE1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Score whereE2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Score whereE3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Score whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Score whereMatchDayId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Score whereN($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Score whereStartnumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Score whereToestel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Score whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Score whereUpdatedAt($value)
 */
	class Score extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models{
/**
 * App\Models\ScoreCorrection
 *
 * @property-read mixed $e
 * @property-read mixed $e_score
 * @property-read \App\Models\Score|null $score
 * @method static \Illuminate\Database\Eloquent\Builder|ScoreCorrection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ScoreCorrection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ScoreCorrection query()
 */
	class ScoreCorrection extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Setting
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $key
 * @property string|null $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Setting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting query()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereValue($value)
 */
	class Setting extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SyncTask
 *
 * @property int $id
 * @property string $model_type
 * @property int|null $model_id
 * @property string $operation
 * @property mixed $data
 * @property int $synced
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SyncTask newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SyncTask newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SyncTask query()
 * @method static \Illuminate\Database\Eloquent\Builder|SyncTask whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyncTask whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyncTask whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyncTask whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyncTask whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyncTask whereOperation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyncTask whereSynced($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyncTask whereUpdatedAt($value)
 */
	class SyncTask extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Team
 *
 * @property int $id
 * @property int $competition_id
 * @property int $niveau_id
 * @property string $name
 * @property int $performing
 * @property int $counting
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Competition $competition
 * @property-read \App\Models\Niveau $niveau
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Registration> $registrations
 * @property-read int|null $registrations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TeamScore> $team_scores
 * @property-read int|null $team_scores_count
 * @method static \Illuminate\Database\Eloquent\Builder|Team newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Team newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Team onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Team query()
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereCompetitionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereCounting($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereNiveauId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team wherePerforming($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Team withoutTrashed()
 */
	class Team extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models{
/**
 * App\Models\TeamScore
 *
 * @property int $id
 * @property int $team_id
 * @property int $match_day_id
 * @property string $toestel_scores
 * @property float $total_score
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TeamScore newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TeamScore newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TeamScore query()
 * @method static \Illuminate\Database\Eloquent\Builder|TeamScore whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TeamScore whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TeamScore whereMatchDayId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TeamScore whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TeamScore whereToestelScores($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TeamScore whereTotalScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TeamScore whereUpdatedAt($value)
 */
	class TeamScore extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Trainer
 *
 * @property int $id
 * @property string $name
 * @property int|null $club_id
 * @property string|null $email
 * @property string|null $phone
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Club|null $club
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Competition> $competitions
 * @property-read int|null $competitions_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Trainer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Trainer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Trainer onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Trainer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Trainer whereClubId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trainer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trainer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trainer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trainer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trainer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trainer wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trainer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trainer withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Trainer withoutTrashed()
 */
	class Trainer extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property mixed $password
 * @property string|null $remember_token
 * @property int $active
 * @property \Illuminate\Support\Carbon|null $last_seen_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Club> $clubs
 * @property-read int|null $clubs_count
 * @property-read mixed $is_jury
 * @property-read mixed $is_trainer
 * @property-read \App\Models\Jury|null $jury
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserSetting> $settings
 * @property-read int|null $settings_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Trainer> $trainers
 * @property-read int|null $trainers_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastSeenAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutRole($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutTrashed()
 */
	class User extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable, \Illuminate\Contracts\Auth\MustVerifyEmail {}
}

namespace App\Models{
/**
 * App\Models\UserSetting
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $key
 * @property string|null $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting whereValue($value)
 */
	class UserSetting extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Wedstrijd
 *
 * @property int $id
 * @property int $match_day_id
 * @property int $index
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read mixed $group_amount
 * @property-read mixed $groups
 * @property-read mixed $niveaus_list
 * @property-read \App\Models\MatchDay $match_day
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Niveau> $niveaus
 * @property-read int|null $niveaus_count
 * @method static \Illuminate\Database\Eloquent\Builder|Wedstrijd newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Wedstrijd newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Wedstrijd onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Wedstrijd query()
 * @method static \Illuminate\Database\Eloquent\Builder|Wedstrijd whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wedstrijd whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wedstrijd whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wedstrijd whereIndex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wedstrijd whereMatchDayId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wedstrijd whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wedstrijd withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Wedstrijd withoutTrashed()
 */
	class Wedstrijd extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

