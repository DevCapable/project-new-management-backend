<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;



class User extends Authenticatable
{

    use Notifiable,HasApiTokens;


    protected $fillable = [
        'name',
        'email',
        'password',
        'currant_workspace',
        'avatar',
        'type',
        'is_password_reset'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getGuard(){
        return $this->guard;
    }

    public function workspace()
    {
        return $this->belongsToMany('App\Models\Workspace', 'user_workspaces', 'user_id', 'workspace_id')->withPivot('permission');
    }

    public function currentWorkspace()
    {
        return $this->hasOne('App\Models\Workspace', 'id', 'currant_workspace');
    }



    public function countProject($workspace_id = '')
    {
        $count = UserProject::join('projects', 'projects.id', '=', 'user_projects.project_id')->where('user_projects.user_id', '=', $this->id);
        if(!empty($workspace_id))
        {
            $count->where('projects.workspace', '=', $workspace_id);
        }

        return $count->count();
    }

    public function countWorkspaceProject($workspace_id)
    {
        return Project::join('workspaces', 'workspaces.id', '=', 'projects.workspace')->where('workspaces.id', '=', $workspace_id)->count();
    }

    public function countWorkspace()
    {
        return Workspace::where('created_by', '=', $this->id)->count();
    }

    public function countUsers($workspace_id)
    {
        $count = UserWorkspace::join('workspaces', 'workspaces.id', '=', 'user_workspaces.workspace_id');
        if(!empty($workspace_id))
        {
            $count->where('workspaces.id', '=', $workspace_id);
        }
        else
        {
            $count->whereIn('workspaces.id', function ($query){
                $query->select('workspace_id')->from('user_workspaces')->where('permission', '=', 'Owner')->where('user_id', '=', $this->id);
            });
        }

        return $count->count();
    }

    public function countClients($workspace_id)
    {
        $count = ClientWorkspace::join('workspaces', 'workspaces.id', '=', 'client_workspaces.workspace_id');
        if(!empty($workspace_id))
        {
            $count->where('workspaces.id', '=', $workspace_id);
        }
        else
        {
            $count->whereIn('workspaces.id', function ($query){
                $query->select('workspace_id')->from('user_workspaces')->where('permission', '=', 'Owner')->where('user_id', '=', $this->id);
            });
        }

        return $count->count();
    }

    public function countTask($workspace_id)
    {
        return Task::join('projects', 'tasks.project_id', '=', 'projects.id')->where('projects.workspace', '=', $workspace_id)->where('tasks.assign_to', '=', $this->id)->count();
    }

    public function unread($workspace_id, $user_id)
    {
        return ChMessage::where('from_id', '=', $this->id)->where('to_id','=',$user_id)->where('seen', '=', 0)->where('workspace_id','=',$workspace_id)->get();
    }

    public function notifications($workspace_id){
        return Notification::where('user_id','=',$this->id)->where('workspace_id','=',$workspace_id)->where('is_read','=',0)->orderBy('id','desc')->get();
    }

    public function getInvoices($workspace_id){
        return Invoice::where('workspace_id','=',$workspace_id)->get();
    }

    public function getPermission($project_id){
        $data = UserProject::where('user_id','=',$this->id)->where('project_id','=',$project_id)->first();
//        if (!$data) {
//           $admin = UserProject::where('user_type','=','admin')->first();
//            return json_decode($admin->permission,true);
//        }
        return json_decode($data->permission,true);
    }




  public static function defaultEmail()
    {
        // Email Template
        $emailTemplate = [
            'New Client',
            'Invite User',
            'Assign Project',
            'Contract Share',
        ];

        foreach($emailTemplate as $eTemp)
        {
            EmailTemplate::create(
                [
                    'name' => $eTemp,
                    'from' => env('APP_NAME'),
                ]
            );
        }

        $defaultTemplate = [
            'New Client' => [
                'subject' => 'Login Detail',
                'lang' => [
                    'ar' => '<p>مرحبًا,<b> {user_name} </b>!</p>
                            <p>تفاصيل تسجيل الدخول الخاصة بك لـ<b> {app_name}</b> هي <br></p>
                            <p><b>اسم المستخدم   : </b>{email}</p>
                            <p><b>كلمة المرور    : </b>{password}</p>
                            <p><b>عنوان URL للتطبيق   : </b>{app_url}</p>
                            <p>شكرًا,</p>
                            <p>{app_name}</p>',

                    'da' => '<p>Hej,<b> {user_name} </b>!</p>
                            <p>Dine loginoplysninger til<b> {app_name}</b> er <br></p>
                            <p><b>Brugernavn   : </b>{email}</p>
                            <p><b>Adgangskode   : </b>{password}</p>
                            <p><b>App URL    : </b>{app_url}</p>
                            <p>Tak,</p>
                            <p>{app_name}</p>',

                    'de' => '<p>Hallo,<b> {user_name} </b>!</p>
                            <p>Ihre Anmeldedaten für<b> {app_name}</b> ist <br></p>
                            <p><b>Nutzername   : </b>{email}</p>
                            <p><b>Passwort   : </b>{password}</p>
                            <p><b>App-URL    : </b>{app_url}</p>
                            <p>Vielen Dank,</p>
                            <p>{app_name}</p>',

                    'en' => '<p>Hello,<b> {user_name} </b>!</p>
                            <p>Your login detail for<b> {app_name}</b> is <br></p>
                            <p><b>Username   : </b>{email}</p>
                            <p><b>Password   : </b>{password}</p>
                            <p><b>App Url    : </b>{app_url}</p>
                            <p>Thanks,</p>
                            <p>{app_name}</p>',

                    'es' => '<p>Hola,<b> {user_name} </b>!</p>
                            <p>Su información de inicio de sesión para <b> {app_name}</b> es <br></p>
                            <p><b>Nombre de usuario   : </b>{email}</p>
                            <p><b>Clave     : </b>{password}</p>
                            <p><b>URL de la aplicación    : </b>{app_url}</p>
                            <p>Gracias,</p>
                            <p>{app_name}</p>',

                    'fr' => '<p>Bonjour,<b> {user_name} </b>!</p>
                            <p>Vos identifiants de connexion pour<b> {app_name}</b> est <br></p>
                            <p><b>e-mail   : </b>{email}</p>
                            <p><b>Mot de passe   : </b>{password}</p>
                            <p><b>URL    : </b>{app_url}</p>
                            <p>Merci,</p>
                            <p>{app_name}</p>',

                    'it' => '<p>Ciao,<b> {user_name} </b>!</p>
                            <p>I tuoi dati di accesso per<b> {app_name}</b> è <br></p>
                            <p><b>Nome utente   : </b>{email}</p>
                            <p><b>Parola d\'ordine   : </b>{password}</p>
                            <p><b>URL dell\'app    : </b>{app_url}</p>
                            <p>Grazie,</p>
                            <p>{app_name}</p>',

                    'ja' => '<p>こんにちは,<b> {user_name} </b>!</p>
                            <p>のログイン詳細 <b> {app_name}</b> は <br></p>
                            <p><b>ユーザー名   : </b>{email}</p>
                            <p><b>パスワード   : </b>{password}</p>
                            <p><b>アプリのURL    : </b>{app_url}</p>
                            <p>ありがとう,</p>
                            <p>{app_name}</p>',

                    'nl' => '<p>Hallo,<b> {user_name} </b>!</p>
                            <p>Uw inloggegevens voor<b> {app_name}</b> is <br></p>
                            <p><b>gebruikersnaam   : </b>{email}</p>
                            <p><b>Wachtwoord   : </b>{password}</p>
                            <p><b>App-URL    : </b>{app_url}</p>
                            <p>Bedankt,</p>
                            <p>{app_name}</p>',

                    'pl' => '<p>Witam,<b> {user_name} </b>!</p>
                            <p>Twoje dane logowania do<b> {app_name}</b> jest <br></p>
                            <p><b>Nazwa użytkownika   : </b>{email}</p>
                            <p><b>Hasło   : </b>{password}</p>
                            <p><b>URL aplikacji    : </b>{app_url}</p>
                            <p>Dziękuję,</p>
                            <p>{app_name}</p>',

                    'ru' => '<p>Привет,<b> {user_name} </b>!</p>
                            <p>Ваши данные для входа в<b> {app_name}</b> является <br></p>
                            <p><b>Имя пользователя   : </b>{email}</p>
                            <p><b>Пароль   : </b>{password}</p>
                            <p><b>URL-адрес приложения    : </b>{app_url}</p>
                            <p>Спасибо,</p>
                            <p>{app_name}</p>',

                    'pt' => '<p>Olá,<b> {user_name} </b>!</p>
                            <p>Seus detalhes de login para<b> {app_name}</b> é <br></p>
                            <p><b>Nome de usuário   : </b>{email}</p>
                            <p><b>Senha   : </b>{password}</p>
                            <p><b>URL do aplicativo : </b>{app_url}</p>
                            <p>Obrigada,</p>
                            <p>{app_name}</p>',
                ],
            ],
                 'Invite User' => [
                'subject' => 'New Workspace Invitation',
                'lang' => [
                    'ar' => '<p>مرحبًا,{user_name} !&nbsp;<br>مرحبا بك في {app_name}.</p>
                            <p>انت مدعو،<br>في مساحة عمل جديدة<strong>{workspace_name}</strong></p>
                            <p>بواسطة <strong>{owner_name}.<strong></strong></strong></p>
                            <p style=""><b style="font-weight: bold;">عنوان URL للتطبيق : </b>{app_url}</p>
                            <p style="">شكرًا,</p>
                            <p style="">{app_name}</p>',

                    'da' => '<p>Hej,{user_name} !&nbsp;<br>Velkommen til {app_name}.</p>
                            <p>Du er inviteret,<br>ind i det nye arbejdsområde <strong>{workspace_name}</strong></p>
                            <p>ved <strong>{owner_name}.<strong></strong></strong></p>
                            <p style=""><b style="font-weight: bold;">App URL : </b>{app_url}</p>
                            <p style="">Tak,</p>
                            <p style="">{app_name}</p>',

                    'de' => '<p>Hallo,{user_name} !&nbsp;<br>Willkommen zu {app_name}.</p>
                            <p>Du bist eingeladen,<br>in den neuen Arbeitsbereich <strong>{workspace_name}</strong></p>
                            <p>durch <strong>{owner_name}.<strong></strong></strong></p>
                            <p style=""><b style="font-weight: bold;">App-URL : </b>{app_url}</p>
                            <p style="">Vielen Dank,</p>
                            <p style="">{app_name}</p>',

                    'en' => '<p>Hello,{user_name} !&nbsp;<br>Welcome to {app_name}.</p>
                            <p>You are invited,<br>into new Workspace <strong>{workspace_name}</strong></p>
                            <p>by <strong>{owner_name}.<strong></strong></strong></p>
                            <p style=""><b style="font-weight: bold;">App Url : </b>{app_url}</p>
                            <p style="">Thanks,</p>
                            <p style="">{app_name}</p>',

                    'es' => '<p>Hola,{user_name} !&nbsp;<br>Bienvenido a {app_name}.</p>
                            <p>Estas invitado,<br>en el nuevo espacio de trabajo <strong>{workspace_name}</strong></p>
                            <p>por <strong>{owner_name}.<strong></strong></strong></p>
                            <p style=""><b style="font-weight: bold;">URL de la aplicación : </b>{app_url}</p>
                            <p style="">Gracias,</p>
                            <p style="">{app_name}</p>',

                    'fr' => '<p>Bonjour,{user_name} !&nbsp;<br>Bienvenue à {app_name}.</p>
                            <p>Tu es invité,<br>dans le nouvel espace de travail<strong>{workspace_name}</strong></p>
                            <p>par <strong>{owner_name}.<strong></strong></strong></p>
                            <p style=""><b style="font-weight: bold;">URL: </b>{app_url}</p>
                            <p style="">Merci,</p>
                            <p style="">{app_name}</p>',

                    'it' => '<p>Ciao,{user_name} !&nbsp;<br>Benvenuto a {app_name}.</p>
                            <p>Sei invitato,<br>nel nuovo spazio di lavoro <strong>{workspace_name}</strong></p>
                            <p>di <strong>{owner_name}.<strong></strong></strong></p>
                            <p style=""><b style="font-weight: bold;">URL dell\'app : </b>{app_url}</p>
                            <p style="">Grazie,</p>
                            <p style="">{app_name}</p>',

                    'ja' => '<p>こんにちは,{user_name} !&nbsp;<br>ようこそ {app_name}.</p>
                            <p>あなたは招待されました 、,<br>新しいワークスペースに<strong>{workspace_name}</strong></p>
                            <p>に <strong>{owner_name}.<strong></strong></strong></p>
                            <p style=""><b style="font-weight: bold;">アプリのURL : </b>{app_url}</p>
                            <p style="">ありがとう,</p>
                            <p style="">{app_name}</p>',

                    'nl' => '<p>Hallo,{user_name} !&nbsp;<br>Welkom bij {app_name}.</p>
                            <p>Je bent uitgenodigd,<br>naar nieuwe werkruimte<strong>{workspace_name}</strong></p>
                            <p>door <strong>{owner_name}.<strong></strong></strong></p>
                            <p style=""><b style="font-weight: bold;">App-URL : </b>{app_url}</p>
                            <p style="">Bedankt,</p>
                            <p style="">{app_name}</p>',

                    'pl' => '<p>Witam,{user_name} !&nbsp;<br>Witamy w {app_name}.</p>
                            <p>Jesteś zaproszony,<br>do nowej przestrzeni roboczej <strong>{workspace_name}</strong></p>
                            <p>za pomocą <strong>{owner_name}.<strong></strong></strong></p>
                            <p style=""><b style="font-weight: bold;">URL aplikacji : </b>{app_url}</p>
                            <p style="">Dziękuję,</p>
                            <p style="">{app_name}</p>',

                    'ru' => '<p>Привет,{user_name} !&nbsp;<br>Добро пожаловать в {app_name}.</p>
                            <p>Вы приглашены,<br>в новую рабочую область<strong>{workspace_name}</strong></p>
                            <p>по <strong>{owner_name}.<strong></strong></strong></p>
                            <p style=""><b style="font-weight: bold;">URL-адрес приложения : </b>{app_url}</p>
                            <p style="">Спасибо,</p>
                            <p style="">{app_name}</p>',

                    'pt' => '<p>Olá,{user_name} !&nbsp;<br>Bem-vindo ao {app_name}.</p>
                            <p>Você está convidado,<br>no novo espaço de trabalho <strong>{workspace_name}</strong></p>
                            <p>por <strong>{owner_name}.<strong></strong></strong></p>
                            <p style=""><b style="font-weight: bold;">URL do aplicativo : </b>{app_url}</p>
                            <p style="">Obrigada,</p>
                            <p style="">{app_name}</p>',
                ],
            ],
            'Assign Project' => [
                'subject' => 'New Project Assign',
                'lang' => [
                    'ar' => '<p>مرحبًا,<b>{user_name}</b> !&nbsp;&nbsp;</p><p>مرحبا بك في {app_name}.</p>
                            <p>أنت مدعو إلى مشروع جديد بواسطة <strong>{owner_name}.</strong> <br/></p>
                            <p><b>اسم المشروع   : </b>{project_name}</p>
                            <p><b>حالة المشروع : </b>{project_status}</p>
                            <p><b>عنوان URL للتطبيق        : </b>{app_url}</p>
                            <p>شكرًا,</p>
                            <p>{app_name}</p>',

                    'da' => '<p>Hej,<b>{user_name}</b> !&nbsp;&nbsp;</p><p>Velkommen til {app_name}.</p>
                            <p>Du er inviteret ind i nyt projekt af <strong>{owner_name}.</strong> <br/></p>
                            <p><b>Projekt navn   : </b>{project_name}</p>
                            <p><b>Projektstatus : </b>{project_status}</p>
                            <p><b>App URL        : </b>{app_url}</p>
                            <p>Tak,</p>
                            <p>{app_name}</p>',

                    'de' => '<p>Hallo,<b>{user_name}</b> !&nbsp;&nbsp;</p><p>Willkommen zu{app_name}.</p>
                            <p>Sie werden in ein neues Projekt von eingeladen <strong>{owner_name}.</strong> <br/></p>
                            <p><b>Projektname   : </b>{project_name}</p>
                            <p><b>Projekt-Status : </b>{project_status}</p>
                            <p><b>App-URL        : </b>{app_url}</p>
                            <p>Vielen Dank,</p>
                            <p>{app_name}</p>',

                    'en' => '<p>Hello,<b>{user_name}</b> !&nbsp;&nbsp;</p><p>Welcome to {app_name}.</p>
                            <p>You are invited,into new Project by <strong>{owner_name}.</strong> <br/></p>
                            <p><b>Project Name   : </b>{project_name}</p>
                            <p><b>Project Status : </b>{project_status}</p>
                            <p><b>App Url        : </b>{app_url}</p>
                            <p>Thanks,</p>
                            <p>{app_name}</p>',

                    'es' => '<p>Hola,<b>{user_name}</b> !&nbsp;&nbsp;</p><p>Bienvenido a {app_name}.</p>
                            <p>Estás invitado a un nuevo proyecto por <strong>{owner_name}.</strong> <br/></p>
                            <p><b>Nombre del proyecto   : </b>{project_name}</p>
                            <p><b>Estado del proyecto : </b>{project_status}</p>
                            <p><b>URL de la aplicación  : </b>{app_url}</p>
                            <p>Gracias,</p>
                            <p>{app_name}</p>',

                    'fr' => '<p>Bonjour,<b>{user_name}</b> !&nbsp;&nbsp;</p><p>Bienvenue à {app_name}.</p>
                            <p>Vous êtes invité à un nouveau projet par<strong>{owner_name}.</strong> <br/></p>
                            <p><b>Nom du projet  : </b>{project_name}</p>
                            <p><b>L\'état du projet : </b>{project_status}</p>
                            <p><b>URL de l\'application       : </b>{app_url}</p>
                            <p>Merci,</p>
                            <p>{app_name}</p>',

                    'it' => '<p>Ciao,<b>{user_name}</b> !&nbsp;&nbsp;</p><p>Benvenuto a {app_name}.</p>
                            <p>Sei stato invitato in un nuovo progetto da <strong>{owner_name}.</strong> <br/></p>
                            <p><b>Nome del progetto   : </b>{project_name}</p>
                            <p><b>Stato del progetto : </b>{project_status}</p>
                            <p><b>URL dell\'app       : </b>{app_url}</p>
                            <p>Grazie,</p>
                            <p>{app_name}</p>',

                    'ja' => '<p>こんにちは,<b>{user_name}</b> !&nbsp;&nbsp;</p><p>ようこそ {app_name}.</p>
                            <p>あなたは、によって新しいプロジェクトに招待されます <strong>{owner_name}.</strong> <br/></p>
                            <p><b>プロジェクト名  : </b>{project_name}</p>
                            <p><b>プロジェクトの状況 : </b>{project_status}</p>
                            <p><b>アプリのURL        : </b>{app_url}</p>
                            <p>ありがとう,</p>
                            <p>{app_name}</p>',

                    'nl' => '<p>Hallo,<b>{user_name}</b> !&nbsp;&nbsp;</p><p>Welkom bij{app_name}.</p>
                            <p>Je bent uitgenodigd voor een nieuw project door <strong>{owner_name}.</strong> <br/></p>
                            <p><b>Naam van het project   : </b>{project_name}</p>
                            <p><b>Project status : </b>{project_status}</p>
                            <p><b>App-URL        : </b>{app_url}</p>
                            <p>Bedankt,</p>
                            <p>{app_name}</p>',

                    'pl' => '<p>Witam,<b>{user_name}</b> !&nbsp;&nbsp;</p><p>Witamy w {app_name}.</p>
                            <p>Zapraszamy do nowego projektu przez <strong>{owner_name}.</strong> <br/></p>
                            <p><b>Nazwa Projektu: </b>{project_name}</p>
                            <p><b>Stan projektu : </b>{project_status}</p>
                            <p><b>URL aplikacji : </b>{app_url}</p>
                            <p>Dziękuję,</p>
                            <p>{app_name}</p>',

                    'ru' => '<p>Привет,<b>{user_name}</b> !&nbsp;&nbsp;</p><p>Добро пожаловать в {app_name}.</p>
                            <p>Вы приглашены в новый проект <strong>{owner_name}.</strong> <br/></p>
                            <p><b>Название проекта   : </b>{project_name}</p>
                            <p><b>Статус проекта : </b>{project_status}</p>
                            <p><b>URL-адрес приложения : </b>{app_url}</p>
                            <p>Спасибо,</p>
                            <p>{app_name}</p>',

                    'pt' => '<p>Olá,<b>{user_name}</b> !&nbsp;&nbsp;</p><p>Bem-vindo ao{app_name}.</p>
                            <p>Você está convidado para um novo projeto por <strong>{owner_name}.</strong> <br/></p>
                            <p><b>Nome do Projeto : </b>{project_name}</p>
                            <p><b>Status do projeto : </b>{project_status}</p>
                            <p><b>URL do aplicativo : </b>{app_url}</p>
                            <p>Obrigada,</p>
                            <p>{app_name}</p>',
                ],
            ],


            'Contract Share' => [
                'subject' => 'Contract Share',
                'lang' => ['ar' => '<p><span style="font-size: 14px; font-family: sans-serif;">مرحبًا,<b>{client_name}!</b></span>
                            <br style="font-size: 14px; font-family: sans-serif;">
                            <span style="font-size: 14px; font-family: sans-serif;">تم تعيين عقد جديد لك.</span>
                            </p><p><span style="font-size: 14px; font-family: sans-serif;">
                              <b>موضوع العقد</b> : { contract_subject }<br>
                            <b>اسم المشروع</b> :   {project_name}<br>
                            <b>نوع العقد</b> : {contract_type}<br>
                            <b>القيمة</b> : {value}<br>
                            <b>تاريخ البدء</b> : {start_date}<br>
                            <b>تاريخ الانتهاء</b> : {end_date}</span></p><p><br></p>',

                    'da' => '<p><span style="font-size: 14px; font-family: sans-serif;">Hej,<b>{client_name}!</b></span>
                            <br style="font-size: 14px; font-family: sans-serif;">
                            <span style="font-size: 14px; font-family: sans-serif;">Ny kontrakt er blevet tildelt dig.</span>
                            </p><p><span style="font-size: 14px; font-family: sans-serif;">
                             <b>Kontraktens emne </b> : { contract_subject }<br>
                            <b>Projekt navn</b> :   {project_name}<br>
                            <b>Kontrakttype</b> : {contract_type}<br>
                            <b>værdi</b> : {value}<br>
                            <b>Start dato</b> : {start_date}<br>
                            <b>Slutdato</b> : {end_date}</span></p><p><br></p>',

                    'de' => '<p><span style="font-size: 14px; font-family: sans-serif;">Hallo,<b>{client_name}!</b></span>
                            <br style="font-size: 14px; font-family: sans-serif;">
                            <span style="font-size: 14px; font-family: sans-serif;">Ihnen wurde ein neuer Vertrag zugewiesen.</span>
                            </p><p><span style="font-size: 14px; font-family: sans-serif;">
                            <b>Vertragsgegenstand </b> : {contract_subject}<br>
                            <b>Projektname</b> :   {project_name}<br>
                            <b>Vertragstyp</b> : {contract_type}<br>
                            <b>Wert</b> : {value}<br>
                            <b>Anfangsdatum</b> : {start_date}<br>
                            <b>Endtermin</b> : {end_date}</span></p><p><br></p>',

                    'en' => '<p><span style="font-size: 14px; font-family: sans-serif;">Hello, <b>{client_name}!</b></span>
                            <br style="font-size: 14px; font-family: sans-serif;">
                            <span style="font-size: 14px; font-family: sans-serif;">New Contract has been Assign to you.</span>
                            </p><p><span style="font-size: 14px; font-family: sans-serif;">
                            <b>Contract Subject</b> : {contract_subject}<br>
                            <b>Project Name</b> :   {project_name}<br>
                            <b>Contract Type</b> : {contract_type}<br>
                            <b>value</b> : {value}<br>
                            <b>Start date</b> : {start_date}<br>
                            <b>End date</b> : {end_date}</span></p><p><br></p>',

                    'es' => '<p><span style="font-size: 14px; font-family: sans-serif;">Hola,<b>{client_name}!</b></span>
                            <br style="font-size: 14px; font-family: sans-serif;">
                            <span style="font-size: 14px; font-family: sans-serif;">Se le ha asignado un nuevo contrato.</span>
                            </p><p><span style="font-size: 14px; font-family: sans-serif;">
                              <b>Objeto del contrato</b> : {contract_subject}<br>
                            <b>Nombre del proyecto</b> :   {project_name}<br>
                            <b>tipo de contrato</b> : {contract_type}<br>
                            <b>valor</b> : {value}<br>
                            <b>Fecha de inicio</b> : {start_date}<br>
                            <b>Fecha final</b> : {end_date}</span></p><p><br></p>',

                    'fr' => '<p><span style="font-size: 14px; font-family: sans-serif;">Bonjour,<b>{client_name}!</b></span>
                            <br style="font-size: 14px; font-family: sans-serif;">
                            <span style="font-size: 14px; font-family: sans-serif;">Un nouveau contrat vous a été attribué.</span>
                            </p><p><span style="font-size: 14px; font-family: sans-serif;">
                             <b>Objet du contrat</b> : {contract_subject}<br>
                            <b>nom du projet</b> :   {project_name}<br>
                            <b>Type de contrat</b> : {contract_type}<br>
                            <b>évaluer</b> : {value}<br>
                            <b>Date de début</b> : {start_date}<br>
                            <b>Date de fin</b> : {end_date}</span></p><p><br></p>',

                    'it' => '<p><span style="font-size: 14px; font-family: sans-serif;">Ciao,<b>{client_name}!</b></span>
                            <br style="font-size: 14px; font-family: sans-serif;">
                            <span style="font-size: 14px; font-family: sans-serif;">Ti è stato assegnato un nuovo contratto.</span>
                            </p><p><span style="font-size: 14px; font-family: sans-serif;">
                             <b>Oggetto del contratto</b> : {contract_subject}<br>
                            <b>Nome del progetto</b> :   {project_name}<br>
                            <b>tipo di contratto</b> : {contract_type}<br>
                            <b>valore</b> : {value}<br>
                            <b>Data d\'inizio</b> : {start_date}<br>
                            <b>Data di fine</b> : {end_date}</span></p><p><br></p>',

                    'ja' => '<p><span style="font-size: 14px; font-family: sans-serif;">こんにちは,<b>{client_name}!</b></span>
                            <br style="font-size: 14px; font-family: sans-serif;">
                            <span style="font-size: 14px; font-family: sans-serif;">新しい契約があなたに割り当てられました.</span>
                            </p><p><span style="font-size: 14px; font-family: sans-serif;">
                             <b>契約対象</b> : {contract_subject}<br>
                            <b>プロジェクト名</b> :   {project_name}<br>
                            <b>契約の種類</b> : {contract_type}<br>
                            <b>価値</b> : {value}<br>
                            <b>開始日</b> : {start_date}<br>
                            <b>終了日</b> : {end_date}</span></p><p><br></p>',

                    'nl' => '<p><span style="font-size: 14px; font-family: sans-serif;">Hallo,<b>{client_name}!</b></span>
                            <br style="font-size: 14px; font-family: sans-serif;">
                            <span style="font-size: 14px; font-family: sans-serif;">Nieuw contract is aan u toegewezen.</span>
                            </p><p><span style="font-size: 14px; font-family: sans-serif;">
                             <b>Contractonderwerp</b> : {contract_subject}<br>
                            <b>Naam van het project</b> :   {project_name}<br>
                            <b>Contract type</b> : {contract_type}<br>
                            <b>waarde</b> : {value}<br>
                            <b>Startdatum</b> : {start_date}<br>
                            <b>Einddatum</b> : {end_date}</span></p><p><br></p>',

                    'pl' => '<p><span style="font-size: 14px; font-family: sans-serif;">Witam,<b>{client_name}!</b></span>
                            <br style="font-size: 14px; font-family: sans-serif;">
                            <span style="font-size: 14px; font-family: sans-serif;">Nowa umowa została Ci przypisana.</span>
                            </p><p><span style="font-size: 14px; font-family: sans-serif;">
                            <b>Przedmiot umowy</b> : {contract_subject}<br>
                            <b>Nazwa Projektu</b> :   {project_name}<br>
                            <b>Typ kontraktu</b> : {contract_type}<br>
                            <b>wartość</b> : {value}<br>
                            <b>Data rozpoczęcia</b> : {start_date}<br>
                            <b>Data zakonczenia</b> : {end_date}</span></p><p><br></p>',

                    'ru' => '<p><span style="font-size: 14px; font-family: sans-serif;">Привет,<b>{client_name}!</b></span>
                            <br style="font-size: 14px; font-family: sans-serif;">
                            <span style="font-size: 14px; font-family: sans-serif;">Вам назначен новый контракт.</span>
                            </p><p><span style="font-size: 14px; font-family: sans-serif;">
                             <b>Предмет договора</b> : {contract_subject}<br>
                            <b>название проекта</b> :   {project_name}<br>
                            <b>Форма контракта</b> : {contract_type}<br>
                            <b>ценность</b> : {value}<br>
                            <b>Дата начала</b> : {start_date}<br>
                            <b>Дата окончания</b> : {end_date}</span></p><p><br></p>',

                    'pt' => '<p><span style="font-size: 14px; font-family: sans-serif;">Olá,<b>{client_name}!</b></span>
                            <br style="font-size: 14px; font-family: sans-serif;">
                            <span style="font-size: 14px; font-family: sans-serif;">Novo contrato foi atribuído a você.</span>
                            </p><p><span style="font-size: 14px; font-family: sans-serif;">
                             <b>Assunto do Contrato</b> : {contract_subject}<br>
                            <b>Nome do Projeto</b> :   {project_name}<br>
                            <b>tipo de contrato</b> : {contract_type}<br>
                            <b>valor</b> : {value}<br>
                            <b>Data de início</b> : {start_date}<br>
                            <b>Data final</b> : {end_date}</span></p><p><br></p>',
                ],
            ],

        ];

        $email = EmailTemplate::all();

        foreach($email as $e)
        {
            foreach($defaultTemplate[$e->name]['lang'] as $lang => $content)
            {
                EmailTemplateLang::create(
                    [
                        'parent_id' => $e->id,
                        'lang' => $lang,
                        'subject' => $defaultTemplate[$e->name]['subject'],
                        'content' => $content,
                        'from' => (env('APP_NAME')) ? env('APP_NAME'):'Taskly',
                    ]
                );
            }
        }

    }










}
