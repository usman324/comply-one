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
 * @property int $id
 * @property int|null $user_id
 * @property string|null $name
 * @property string|null $image
 * @property string|null $avatar
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer byCustomer($shops)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer withoutTrashed()
 */
	class Customer extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $user_id
 * @property string|null $title
 * @property string|null $logo_height
 * @property string|null $logo
 * @property string|null $favicon
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $address
 * @property string|null $facebook
 * @property string|null $mail_template
 * @property string|null $instagram
 * @property string|null $youtube
 * @property string|null $linkdin
 * @property string|null $lat
 * @property string|null $long
 * @property string|null $city
 * @property string|null $street
 * @property string|null $street_no
 * @property string|null $website
 * @property string|null $postal_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GeneralSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GeneralSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GeneralSetting onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GeneralSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GeneralSetting whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GeneralSetting whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GeneralSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GeneralSetting whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GeneralSetting whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GeneralSetting whereFacebook($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GeneralSetting whereFavicon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GeneralSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GeneralSetting whereInstagram($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GeneralSetting whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GeneralSetting whereLinkdin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GeneralSetting whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GeneralSetting whereLogoHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GeneralSetting whereLong($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GeneralSetting whereMailTemplate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GeneralSetting wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GeneralSetting wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GeneralSetting whereStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GeneralSetting whereStreetNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GeneralSetting whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GeneralSetting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GeneralSetting whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GeneralSetting whereWebsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GeneralSetting whereYoutube($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GeneralSetting withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GeneralSetting withoutTrashed()
 */
	class GeneralSetting extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $questionnaire_id
 * @property string|null $question
 * @property string|null $order
 * @property string|null $description
 * @property string $type
 * @property array<array-key, mixed>|null $options
 * @property bool $is_required
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Questionnaire $questionnaire
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereIsRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereQuestionnaireId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question withoutTrashed()
 */
	class Question extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $section
 * @property string|null $question_id
 * @property string $title
 * @property string $status
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon $end_date
 * @property string|null $description
 * @property string|null $message
 * @property string $type
 * @property string|null $options
 * @property int $required
 * @property bool $allow_anonymous
 * @property bool $allow_multiple_responses
 * @property bool $show_progress
 * @property bool $randomize_questions
 * @property string|null $placeholder
 * @property string|null $depends_on
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Question> $questions
 * @property-read int|null $questions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\QuestionnaireResponse> $responses
 * @property-read int|null $responses_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Questionnaire bySection(string $section)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Questionnaire newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Questionnaire newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Questionnaire onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Questionnaire query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Questionnaire whereAllowAnonymous($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Questionnaire whereAllowMultipleResponses($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Questionnaire whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Questionnaire whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Questionnaire whereDependsOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Questionnaire whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Questionnaire whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Questionnaire whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Questionnaire whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Questionnaire whereOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Questionnaire wherePlaceholder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Questionnaire whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Questionnaire whereRandomizeQuestions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Questionnaire whereRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Questionnaire whereSection($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Questionnaire whereShowProgress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Questionnaire whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Questionnaire whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Questionnaire whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Questionnaire whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Questionnaire whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Questionnaire whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Questionnaire withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Questionnaire withoutTrashed()
 */
	class Questionnaire extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $questionnaire_id
 * @property string $section
 * @property string $question_id
 * @property array<array-key, mixed>|null $answer
 * @property string|null $answer_text
 * @property \Illuminate\Support\Carbon|null $answered_at
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Questionnaire $questionnaire
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionnaireResponse byQuestion(string $questionId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionnaireResponse bySection(string $section)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionnaireResponse forUser(int $userId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionnaireResponse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionnaireResponse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionnaireResponse onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionnaireResponse query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionnaireResponse whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionnaireResponse whereAnswerText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionnaireResponse whereAnsweredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionnaireResponse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionnaireResponse whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionnaireResponse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionnaireResponse whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionnaireResponse whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionnaireResponse whereQuestionnaireId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionnaireResponse whereSection($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionnaireResponse whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionnaireResponse whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionnaireResponse whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionnaireResponse withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionnaireResponse withoutTrashed()
 */
	class QuestionnaireResponse extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $name
 * @property string|null $username
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $image
 * @property string|null $phone
 * @property string|null $avatar
 * @property string|null $address
 * @property string $email
 * @property string|null $otp
 * @property string|null $dob
 * @property string|null $joining_date
 * @property string|null $country
 * @property string|null $city
 * @property string|null $qualification
 * @property string|null $user_number
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $password
 * @property string $status
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $stripe_id
 * @property string|null $pm_type
 * @property string|null $pm_last_four
 * @property string|null $trial_ends_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Cashier\Subscription> $subscriptions
 * @property-read int|null $subscriptions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User byEmail($email)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User byName($name)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User byPhone($phone)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User byStatus($status)
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User hasExpiredGenericTrial()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User onGenericTrial()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereJoiningDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereOtp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePmLastFour($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePmType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereQualification($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole(string $role)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRoleNot(array $role)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStripeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTrialEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUserNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutTrashed()
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $user_id
 * @property string|null $ip
 * @property string|null $type
 * @property string|null $title
 * @property string|null $description
 * @property string|null $model
 * @property string|null $model_id
 * @property string|null $action
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserActivity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserActivity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserActivity onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserActivity query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserActivity whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserActivity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserActivity whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserActivity whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserActivity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserActivity whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserActivity whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserActivity whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserActivity whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserActivity whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserActivity whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserActivity whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserActivity withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserActivity withoutTrashed()
 */
	class UserActivity extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $section
 * @property string $status
 * @property int $total_questions
 * @property int $answered_questions
 * @property numeric $completion_percentage
 * @property \Illuminate\Support\Carbon|null $started_at
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property array<array-key, mixed>|null $metadata
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserQuestionnaireSession bySection(string $section)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserQuestionnaireSession completed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserQuestionnaireSession forUser(int $userId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserQuestionnaireSession inProgress()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserQuestionnaireSession newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserQuestionnaireSession newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserQuestionnaireSession onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserQuestionnaireSession query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserQuestionnaireSession whereAnsweredQuestions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserQuestionnaireSession whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserQuestionnaireSession whereCompletionPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserQuestionnaireSession whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserQuestionnaireSession whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserQuestionnaireSession whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserQuestionnaireSession whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserQuestionnaireSession whereSection($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserQuestionnaireSession whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserQuestionnaireSession whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserQuestionnaireSession whereTotalQuestions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserQuestionnaireSession whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserQuestionnaireSession whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserQuestionnaireSession withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserQuestionnaireSession withoutTrashed()
 */
	class UserQuestionnaireSession extends \Eloquent {}
}

