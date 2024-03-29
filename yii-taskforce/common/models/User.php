<?php
namespace common\models;

use frontend\models\db\Cities;
use frontend\models\db\Replies;
use frontend\models\db\UserFavorites;
use frontend\models\db\UserFiles;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use frontend\models\db\Opinions;
use frontend\models\db\UsersCategories;
use frontend\models\db\Tasks;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $city_id
 * @property string $last_activity
 * @property string $password write-only password
 * @property string $phone
 * @property string $skype
 * @property string $telegram
 * @property string $birthday
 * @property string $about
 * @property boolean $setting_new_message
 * @property boolean $setting_action_task
 * @property boolean $setting_new_review
 * @property boolean $setting_show_contact
 * @property boolean $setting_hide_profile
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%users}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['dt_add'],
                ],
                'value' => new \yii\db\Expression('CURRENT_DATE()'),
            ],
            'timestamp2' => [
                'class' => TimestampBehavior::class,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
            [['name', 'email', 'photo', 'phone', 'skype', 'telegram'], 'string', 'max' => 255],
            [['birthday'], 'date', 'format' => 'php:Y-m-d'],
            [['about'], 'string'],
            [['setting_new_message', 'setting_action_task', 'setting_new_review', 'setting_show_contact', 'setting_hide_profile'], 'boolean'],
            [['city_id'], 'integer']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @param string $email
     * @return User|null
     */
    public static function findByEmail(string $email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token) {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * получение отзывов о юзере
     */
    public function getOpinions()
    {
        return $this->hasMany(Opinions::class, ['user_id' => 'id']);
    }

    /**
     * получение рейтинга юзера по отзывам
     */
    public function getOpinionsRating()
    {
        return round($this->getOpinions()->average('rate'), 2);
    }

    /**
     * получение категорий юзера
     */
    public function getCategories()
    {
        return $this->hasMany(UsersCategories::class, ['user_id' => 'id']);
    }

    /**
     * получение заданий исполнителя
     */
    public function getTasksExecutor()
    {
        return $this->hasMany(Tasks::class, ['executor_id' => 'id']);
    }

    /**
     * получение избранных исполнителей
     */
    public function getFavorites()
    {
        return $this->hasMany(UserFavorites::class, ['user_id' => 'id']);
    }

    /**
     * получение заданий заказчика
     */
    public function getTasksCustomer()
    {
        return $this->hasMany(Tasks::class, ['customer_id' => 'id']);
    }

    /**
     * получение файлов
     */
    public function getFiles()
    {
        return $this->hasMany(UserFiles::class, ['id_user' => 'id']);
    }

    public function getCompletedTask()
    {
        return Tasks::find()->where(['status' => Tasks::STATUS_COMPLETED, 'executor_id' => $this->id])->all();
    }


    public function getCity()
    {
        return $this->hasOne(Cities::class, ['id' => 'city_id']);
    }
}
