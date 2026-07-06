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


namespace App\Models\Auth{
/**
 * @property string $id
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property \Illuminate\Support\Carbon|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereTwoFactorConfirmedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereUsername($value)
 */
	class Admin extends \Eloquent {}
}

namespace App\Models\Auth{
/**
 * @property string $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $two_factor_confirmed_at
 * @property string|null $phone
 * @property string|null $line_user_id
 * @property string|null $remember_token
 * @property int $profile_completed
 * @property int $bookshelf_public
 * @property int $is_client
 * @property int $is_medical
 * @property string|null $agreed_at
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAgreedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereBookshelfPublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsClient($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsMedical($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLineUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereProfileCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorConfirmedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent implements \Illuminate\Contracts\Auth\MustVerifyEmail {}
}

namespace App\Models\Pilates{
/**
 * @property string $id
 * @property string $user_id
 * @property string $name
 * @property string $gender
 * @property string|null $occupation
 * @property string|null $body_notes
 * @property string|null $personality_notes
 * @property bool $is_active
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pilates\PilatesSession> $pilatesSession
 * @property-read int|null $pilates_session_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pilates\Reservation> $reservation
 * @property-read int|null $reservation_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pilates\TrainingLog> $trainingLog
 * @property-read int|null $training_log_count
 * @property-read \App\Models\Auth\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client archived()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereBodyNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereOccupation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client wherePersonalityNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereUserId($value)
 */
	class Client extends \Eloquent {}
}

namespace App\Models\Pilates{
/**
 * @property string $id
 * @property string $client_id
 * @property string|null $goal
 * @property string|null $outlook
 * @property string|null $hope
 * @property \Illuminate\Support\Carbon|null $achieved_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Pilates\Client $client
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientGoal forClient(string $clientId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientGoal latest()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientGoal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientGoal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientGoal query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientGoal whereAchievedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientGoal whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientGoal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientGoal whereGoal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientGoal whereHope($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientGoal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientGoal whereOutlook($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientGoal whereUpdatedAt($value)
 */
	class ClientGoal extends \Eloquent {}
}

namespace App\Models\Pilates{
/**
 * @property-read \App\Models\Pilates\Client|null $client
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GuestPassUsage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GuestPassUsage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GuestPassUsage query()
 */
	class GuestPassUsage extends \Eloquent {}
}

namespace App\Models\Pilates{
/**
 * @property string $id
 * @property string $client_id
 * @property string $intake_form_id
 * @property string $full_name
 * @property \Illuminate\Support\Carbon $date_of_birth
 * @property string|null $contact
 * @property string|null $daily_posture
 * @property array<array-key, mixed> $symptoms
 * @property string|null $symptom_detail
 * @property string|null $medical_history
 * @property string|null $current_treatment
 * @property int $session_goal
 * @property string|null $future_goal
 * @property bool $consent_non_medical
 * @property bool $consent_health_condition
 * @property bool $consent_report_discomfort
 * @property \Illuminate\Support\Carbon|null $consented_at
 * @property \Illuminate\Support\Carbon|null $submitted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Pilates\Client $client
 * @property-read \App\Models\Pilates\IntakeForm $intakeForm
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InitialConsultation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InitialConsultation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InitialConsultation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InitialConsultation whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InitialConsultation whereConsentHealthCondition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InitialConsultation whereConsentNonMedical($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InitialConsultation whereConsentReportDiscomfort($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InitialConsultation whereConsentedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InitialConsultation whereContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InitialConsultation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InitialConsultation whereCurrentTreatment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InitialConsultation whereDailyPosture($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InitialConsultation whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InitialConsultation whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InitialConsultation whereFutureGoal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InitialConsultation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InitialConsultation whereIntakeFormId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InitialConsultation whereMedicalHistory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InitialConsultation whereSessionGoal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InitialConsultation whereSubmittedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InitialConsultation whereSymptomDetail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InitialConsultation whereSymptoms($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InitialConsultation whereUpdatedAt($value)
 */
	class InitialConsultation extends \Eloquent {}
}

namespace App\Models\Pilates{
/**
 * @property string $id
 * @property string $client_id
 * @property \Illuminate\Support\Carbon|null $submitted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Pilates\Client|null $client
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IntakeForm newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IntakeForm newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IntakeForm query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IntakeForm whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IntakeForm whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IntakeForm whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IntakeForm whereSubmittedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IntakeForm whereUpdatedAt($value)
 */
	class IntakeForm extends \Eloquent {}
}

namespace App\Models\Pilates{
/**
 * @property string $id
 * @property string $lesson_template_id
 * @property \Illuminate\Support\Carbon $date
 * @property string|null $location_id
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Pilates\LessonTemplate $lessonTemplate
 * @property-read \App\Models\Pilates\Location|null $location
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pilates\Reservation> $reservations
 * @property-read int|null $reservations_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonSlot available()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonSlot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonSlot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonSlot query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonSlot upcoming()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonSlot whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonSlot whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonSlot whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonSlot whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonSlot whereLessonTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonSlot whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonSlot whereUpdatedAt($value)
 */
	class LessonSlot extends \Eloquent {}
}

namespace App\Models\Pilates{
/**
 * @property string $id
 * @property string $start_time
 * @property string $end_time
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonTemplate whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonTemplate whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonTemplate whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonTemplate whereUpdatedAt($value)
 */
	class LessonTemplate extends \Eloquent {}
}

namespace App\Models\Pilates{
/**
 * @property string $id
 * @property string $name
 * @property string $policy
 * @property string $address
 * @property numeric|null $base_fee
 * @property string|null $map_url
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereBaseFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereMapUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location wherePolicy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereUpdatedAt($value)
 */
	class Location extends \Eloquent {}
}

namespace App\Models\Pilates{
/**
 * @property string $id
 * @property string $client_id
 * @property string $reservation_id
 * @property \Illuminate\Support\Carbon $session_date
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilatesSession client()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilatesSession newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilatesSession newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilatesSession query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilatesSession reservation()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilatesSession whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilatesSession whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilatesSession whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilatesSession whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilatesSession whereReservationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilatesSession whereSessionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilatesSession whereUpdatedAt($value)
 */
	class PilatesSession extends \Eloquent {}
}

namespace App\Models\Pilates{
/**
 * @property string $id
 * @property string $lesson_slot_id
 * @property string $user_id
 * @property int $participants
 * @property string|null $participants_name
 * @property string|null $note
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $cancelled_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Pilates\LessonSlot $lessonSlot
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pilates\Location> $location
 * @property-read int|null $location_count
 * @property-read \App\Models\Auth\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation confirmed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation forUser()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation past()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation upComing()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereCancelledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereLessonSlotId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereParticipants($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereParticipantsName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereUserId($value)
 */
	class Reservation extends \Eloquent {}
}

namespace App\Models\Pilates{
/**
 * @property string $id
 * @property string $client_id
 * @property \Illuminate\Support\Carbon $logged_date
 * @property int $mood
 * @property string|null $free_text
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pilates\TrainingQuestion> $trainingQuestion
 * @property-read int|null $training_question_count
 * @property-read \App\Models\Auth\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingLog forUser()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingLog hasQuestion()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingLog latest()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingLog urgent()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingLog whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingLog whereFreeText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingLog whereLoggedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingLog whereMood($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingLog whereUpdatedAt($value)
 */
	class TrainingLog extends \Eloquent {}
}

namespace App\Models\Pilates{
/**
 * @property string $id
 * @property string $training_log_id
 * @property string $category
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Pilates\TrainingLog $trainingLog
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingLogCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingLogCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingLogCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingLogCategory whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingLogCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingLogCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingLogCategory whereTrainingLogId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingLogCategory whereUpdatedAt($value)
 */
	class TrainingLogCategory extends \Eloquent {}
}

namespace App\Models\Pilates{
/**
 * @property string $id
 * @property string $training_log_id
 * @property string $question
 * @property string $urgency
 * @property string $answer
 * @property \Illuminate\Support\Carbon|null $answered_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Pilates\TrainingLog $trainingLog
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingQuestion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingQuestion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingQuestion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingQuestion whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingQuestion whereAnsweredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingQuestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingQuestion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingQuestion whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingQuestion whereTrainingLogId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingQuestion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingQuestion whereUrgency($value)
 */
	class TrainingQuestion extends \Eloquent {}
}

