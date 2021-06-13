<?php

namespace App\Models;

use App\Core\Database;
use App\Core\FormBuilder;
use App\Core\Traits\ModelsTrait;


class Person extends Database
{
    use ModelsTrait;

    private ?int $id = null;
    private string $createdAt;
    private ?string $updatedAt;
    protected ?string $fullName;
    protected ?string $pseudo;
    protected ?string $email;
    protected ?string $emailkey;
    protected ?string $password;
    protected bool $optin = true;
    protected ?string $deletedAt;
    protected string $role = 'user';
    protected bool $emailConfirmed = false;
    private ?array $actions = [];

    // Foreign properties
    protected int $mediaId;
    public Media $media;

    public function __construct() {
        parent::__construct();
        $this->media = new Media();
        $this->actions = [
            ['name' => 'Modifier','action'=> 'modify', 'url' => '/admin/utilisateurs/modifier'],
            ['name' => 'Supprimer', 'action'=> 'delete', 'url' => '/admin/utilisateurs/supprimer']
        ];
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getFullName(): ?string {
        return $this->fullName;
    }

    public function setFullName($fullName): void {
        $this->fullName = $fullName;
    }

    public function getPseudo(): ?string {
        return $this->pseudo;
    }

    public function setPseudo($pseudo): void {
        $this->pseudo = $pseudo;
    }

    public function getEmail(): ?string {
        return $this->email;
    }

    public function setEmail($email): void {
        $this->email = $email;
    }

    public function getEmailKey() {
        return $this->emailkey;
    }

    public function setEmailKey($emailkey): void {
        $this->emailkey = $emailkey;
    }

    public function getPassword(): ?string {
        return $this->password;
    }

    public function setPassword($password): void {
        $this->password = $password;
    }

    public function getOptin(): bool {
        return $this->optin;
    }

    public function setOptin($optin): void {
        $this->optin = $optin;
    }

    public function getUpdatedAt(): ?string {
        return $this->updatedAt;
    }

    public function setUpdatedAt($updatedAt): void {
        $this->updatedAt = $updatedAt;
    }

    public function getCreatedAt(): string {
        return $this->createdAt;
    }

    public function getDeletedAt(): ?string {
        return $this->deletedAt;
    }

    public function setDeletedAt($deletedAt): void {
        $this->deletedAt = $deletedAt;
    }

    public function getRole(): string {
        return $this->role;
    }

    public function setRole(string $role): void {
        $this->role = $role;
    }

    public function isEmailConfirmed(): bool {
        return $this->emailConfirmed;
    }

    
    public function setEmailConfirmed(bool $emailConfirmed): void {
        $this->emailConfirmed = $emailConfirmed;
    }

    public function getActions(): ?array {
        return $this->actions;
    }

    public function setActions(?array $actions): void {
        $this->actions = $actions;
    }

    public function getMediaId(): int {
        return $this->mediaId;
    }

    public function setMediaId($mediaId): void {
        $this->mediaId = $mediaId;
    }

    public function getMedia(): Media {
        if (!empty($this->mediaId) && is_numeric($this->mediaId))
            $this->media->setId($this->mediaId);
        return $this->media;
    }

    public function isLogged(): bool {
        if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == true) {
            return true;
        } else {
            return false;
        }
    }

    public function setMedia(Media $media): void {
        $this->media = $media;
    }

    public function canAccessBackOffice(): bool {
        if(in_array($this->getRole(), ['admin', 'editor', 'moderator'])) {
            return true;
        } else {
            return false;
        }
    }

    public function isAdmin(): bool {
        if($this->getRole() === 'admin') {
            return true;
        } else {
            return false;
        }
    }

    public function isEditor(): bool {
        if($this->getRole() === 'editor') {
            return true;
        } else {
            return false;
        }
    }

    public function isModerator(): bool {
        if($this->getRole() === 'moderator') {
            return true;
        } else {
            return false;
        }
    }

    public function isVIP(): bool {
        if($this->getRole() === 'vip') {
            return true;
        } else {
            return false;
        }
    }

    public function isUser(): bool {
        if($this->getRole() === 'user') {
            return true;
        } else {
            return false;
        }
    }

    public function checkRights($role): bool {
        switch ($role) {
            case 'moderator':
                if (in_array($this->getRole(), ['admin', 'editor', 'moderator'])) return true;
                break;
            case 'editor':
                if (in_array($this->getRole(), ['admin', 'editor'])) return true;
                break;
            case 'admin':
                if ($this->getRole() == 'admin') return true;
                break;
            default:
                return false;
        }
        return false;
    }

    public function setDefaultProfilePicture() {
        if(file_exists(PATH_TO_IMG.'default.jpg')) {
            $media = new Media();
            if($media->findOneBy('path', 'default.jpg')) {
                $defaultImage = $media->findOneBy('path', 'default.jpg');
                $this->setMediaId($defaultImage->getId());
            } else {
                die("Default image is not in database");
            }
        } else {
            die("Default image file does not exist");
        }
    }

    public function formBuilderLogin(): array {
        return [
            "config" => [
                "method" => "POST",
                "action" => "",
                "class" => "form_control",
                "id" => "form_register",
                "submit" => "Se connecter",
                "referer" => '/connexion'
            ],
            "fields" => [
                "email" => [
                    "type" => "email",
                    "placeholder" => "Email",
                    "class" => "input",
                    "id" => "email",
                    "error" => "Le format du champ e-mail est incorrect",
                    "required" => true,

                ],
                "password" => [
                    "type" => "password",
                    "placeholder" => "Mot de passe",
                    "class" => "input",
                    "error" => "Votre champ mot de passe est vide.",
                    "required" => true,

                ],
                "csrfToken" => [
                    "type" => "hidden",
                    "value" => FormBuilder::generateCSRFToken()
                ],
            ]

        ];
    }

    public function formBuilderRegister(): array {
        return [
            "config" => [
                "method" => "POST",
                "action" => "",
                "class" => "form_control",
                "id" => "form_register",
                "submit" => "Valider",
                "referer" => '/inscription'
            ],
            "fields" => [
                "csrfToken" => [
                    "type" => "hidden",
                    "value" => FormBuilder::generateCSRFToken()
                ],
                "pseudo" => [
                    "type" => "text",
                    "placeholder" => "Pseudonyme",
                    "minLength" => 2,
                    "maxLength" => 25,
                    "class" => "input",
                    "regex" => "/^([a-zA-Z0-9-_]{2,25})$/",
                    "error" => "Votre pseudo doit faire entre 2 et 25 caractères",
                    "required" => true,
                ],
                "email" => [
                    "type" => "email",
                    "placeholder" => "Email",
                    "class" => "input",
                    "maxLength" => 130,
                    "error" => "Votre email doit faire entre 8 et 130 caractères",
                    "required" => true,

                ],
                "pwd" => [
                    "type" => "password",
                    "placeholder" => "Mot de passe",
                    "minLength" => 8,
                    "class" => "input",
                    "regex" => "/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&-])[A-Za-z\d@$!%*?&-]{8,}$/",
                    "error" => "Votre mot de passe doit faire au minimum 8 caractères, comporté au moins une lettre minusucule et majuscule, un chiffre et une lettre spéciale.",
                    "required" => true,

                ],
                "pwdConfirm" => [
                    "type" => "password",
                    "class" => "input",
                    "placeholder" => "Confirmation du mot de passe",
                    "confirm" => "pwd",
                    "error" => "Votre mot de passe de confirmation ne correspond pas",
                    "required" => true,
                ],
            ]
        ];
    }

    public function verificationMail($pseudo, $key): string {
        return "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
        <html xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:o=\"urn:schemas-microsoft-com:office:office\">
         <head> 
          <meta charset=\"UTF-8\"> 
          <meta content=\"width=device-width, initial-scale=1\" name=\"viewport\"> 
          <meta name=\"x-apple-disable-message-reformatting\"> 
          <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\"> 
          <meta content=\"telephone=no\" name=\"format-detection\"> 
          <!--[if (mso 16)]>
            <style type=\"text/css\">
            a {text-decoration: none;}
            </style>
            <![endif]--> 
          <!--[if gte mso 9]><style>sup { font-size: 100% !important; }</style><![endif]--> 
          <!--[if gte mso 9]>
        <xml>
            <o:OfficeDocumentSettings>
            <o:AllowPNG></o:AllowPNG>
            <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
        <![endif]--> 
          <style type=\"text/css\">
        #outlook a {
            padding:0;
        }
        .es-button {
            mso-style-priority:100!important;
            text-decoration:none!important;
        }
        a[x-apple-data-detectors] {
            color:inherit!important;
            text-decoration:none!important;
            font-size:inherit!important;
            font-family:inherit!important;
            font-weight:inherit!important;
            line-height:inherit!important;
        }
        .es-desk-hidden {
            display:none;
            float:left;
            overflow:hidden;
            width:0;
            max-height:0;
            line-height:0;
            mso-hide:all;
        }
        [data-ogsb] .es-button {
            border-width:0!important;
            padding:10px 20px 10px 20px!important;
        }
        @media only screen and (max-width:600px) {p, ul li, ol li, a { line-height:150%!important } h1 { font-size:30px!important; text-align:center; line-height:120%!important } h2 { font-size:26px!important; text-align:center; line-height:120%!important } h3 { font-size:20px!important; text-align:center; line-height:120%!important } .es-header-body h1 a, .es-content-body h1 a, .es-footer-body h1 a { font-size:30px!important } .es-header-body h2 a, .es-content-body h2 a, .es-footer-body h2 a { font-size:26px!important } .es-header-body h3 a, .es-content-body h3 a, .es-footer-body h3 a { font-size:20px!important } .es-menu td a { font-size:16px!important } .es-header-body p, .es-header-body ul li, .es-header-body ol li, .es-header-body a { font-size:16px!important } .es-content-body p, .es-content-body ul li, .es-content-body ol li, .es-content-body a { font-size:16px!important } .es-footer-body p, .es-footer-body ul li, .es-footer-body ol li, .es-footer-body a { font-size:16px!important } .es-infoblock p, .es-infoblock ul li, .es-infoblock ol li, .es-infoblock a { font-size:12px!important } *[class=\"gmail-fix\"] { display:none!important } .es-m-txt-c, .es-m-txt-c h1, .es-m-txt-c h2, .es-m-txt-c h3 { text-align:center!important } .es-m-txt-r, .es-m-txt-r h1, .es-m-txt-r h2, .es-m-txt-r h3 { text-align:right!important } .es-m-txt-l, .es-m-txt-l h1, .es-m-txt-l h2, .es-m-txt-l h3 { text-align:left!important } .es-m-txt-r img, .es-m-txt-c img, .es-m-txt-l img { display:inline!important } .es-button-border { display:block!important } a.es-button, button.es-button { font-size:20px!important; display:block!important; border-width:10px 0px 10px 0px!important } .es-adaptive table, .es-left, .es-right { width:100%!important } .es-content table, .es-header table, .es-footer table, .es-content, .es-footer, .es-header { width:100%!important; max-width:600px!important } .es-adapt-td { display:block!important; width:100%!important } .adapt-img { width:100%!important; height:auto!important } .es-m-p0 { padding:0px!important } .es-m-p0r { padding-right:0px!important } .es-m-p0l { padding-left:0px!important } .es-m-p0t { padding-top:0px!important } .es-m-p0b { padding-bottom:0!important } .es-m-p20b { padding-bottom:20px!important } .es-mobile-hidden, .es-hidden { display:none!important } tr.es-desk-hidden, td.es-desk-hidden, table.es-desk-hidden { width:auto!important; overflow:visible!important; float:none!important; max-height:inherit!important; line-height:inherit!important } tr.es-desk-hidden { display:table-row!important } table.es-desk-hidden { display:table!important } td.es-desk-menu-hidden { display:table-cell!important } .es-menu td { width:1%!important } table.es-table-not-adapt, .esd-block-html table { width:auto!important } table.es-social { display:inline-block!important } table.es-social td { display:inline-block!important } }
        </style> 
         </head> 
         <body style=\"width:100%;font-family:arial, 'helvetica neue', helvetica, sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0\"> 
          <div class=\"es-wrapper-color\" style=\"background-color:#F6F6F6\"> 
           <!--[if gte mso 9]>
                    <v:background xmlns:v=\"urn:schemas-microsoft-com:vml\" fill=\"t\">
                        <v:fill type=\"tile\" color=\"#f6f6f6\"></v:fill>
                    </v:background>
                <![endif]--> 
           <table cellpadding=\"0\" cellspacing=\"0\" class=\"es-wrapper\" width=\"100%\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;padding:0;Margin:0;width:100%;height:100%;background-repeat:repeat;background-position:center top\"> 
             <tr> 
              <td valign=\"top\" style=\"padding:0;Margin:0\"> 
               <table cellpadding=\"0\" cellspacing=\"0\" class=\"es-content\" align=\"center\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%\"> 
                 <tr> 
                  <td align=\"center\" style=\"padding:0;Margin:0\"> 
                   <table class=\"es-content-body\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:transparent;width:600px\"> 
                     <tr> 
                      <td align=\"left\" style=\"Margin:0;padding-top:20px;padding-bottom:20px;padding-left:20px;padding-right:20px\"> 
                       <!--[if mso]><table style=\"width:560px\" cellpadding=\"0\" cellspacing=\"0\"><tr><td style=\"width:356px\" valign=\"top\"><![endif]--> 
                       <table cellpadding=\"0\" cellspacing=\"0\" class=\"es-left\" align=\"left\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:left\"> 
                         <tr> 
                          <td class=\"es-m-p0r es-m-p20b\" valign=\"top\" align=\"center\" style=\"padding:0;Margin:0;width:356px\"> 
                           <table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px\"> 
                             <tr> 
                              <td align=\"center\" style=\"padding:0;Margin:0;display:none\"></td> 
                             </tr> 
                           </table></td> 
                         </tr> 
                       </table> 
                       <!--[if mso]></td><td style=\"width:20px\"></td><td style=\"width:184px\" valign=\"top\"><![endif]--> 
                       <table cellpadding=\"0\" cellspacing=\"0\" align=\"right\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px\"> 
                         <tr> 
                          <td align=\"left\" style=\"padding:0;Margin:0;width:184px\"> 
                           <table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px\"> 
                             <tr> 
                              <td align=\"center\" style=\"padding:0;Margin:0;display:none\"></td> 
                             </tr> 
                           </table></td> 
                         </tr> 
                       </table> 
                       <!--[if mso]></td></tr></table><![endif]--></td> 
                     </tr> 
                   </table></td> 
                 </tr> 
               </table> 
               <table cellpadding=\"0\" cellspacing=\"0\" class=\"es-content\" align=\"center\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%\"> 
                 <tr> 
                  <td align=\"center\" style=\"padding:0;Margin:0\"> 
                   <table bgcolor=\"#ffffff\" class=\"es-content-body\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;width:600px\"> 
                     <tr> 
                      <td align=\"left\" style=\"Margin:0;padding-top:20px;padding-bottom:20px;padding-left:20px;padding-right:20px\"> 
                       <table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px\"> 
                         <tr> 
                          <td align=\"center\" valign=\"top\" style=\"padding:0;Margin:0;width:560px\"> 
                           <table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" role=\"presentation\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px\"> 
                             <tr> 
                              <td align=\"left\" style=\"padding:0;Margin:0;padding-bottom:15px\"><h2 style=\"Margin:0;line-height:29px;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;font-size:24px;font-style:normal;font-weight:normal;color:#333333\">Email de confirmation</h2></td> 
                             </tr> 
                             <tr> 
                              <td align=\"left\" style=\"padding:0;Margin:0;padding-top:20px\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;font-size:14px\">Votre demande de création de compte pour Ultraviolet à bien été enregistrée. Pour valider votre compte, merci de cliquer sur le lien ci-dessous :</p></td> 
                             </tr> 
                             <tr> 
                              <td align=\"left\" style=\"padding:0;Margin:0;padding-top:20px\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;font-size:14px\">http://localhost:8080/utilisateurs/verification/$pseudo.'-'.$key</p></td> 
                             </tr> 
                             <tr> 
                              <td align=\"left\" style=\"padding:0;Margin:0;padding-top:15px\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;font-size:14px\">Dans la plupart des logiciels de courriel, cette adresse devrait apparaître comme un lien de couleur bleue qu'il vous suffit de cliquer. Si cel ane fonctionne pas, copiez ce lien et collez-le dans la barre d'adresse de votre navigateur web. </p></td> 
                             </tr> 
                             <tr> 
                              <td align=\"left\" style=\"padding:0;Margin:0;padding-top:20px\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;font-size:14px\">Cordialement, Ultraviolet<br><br></p></td> 
                             </tr> 
                             <tr> 
                              <td align=\"left\" style=\"padding:0;Margin:0\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;font-size:14px\">Ce mail est un mail automatique. Merci de ne pas y répondre.</p></td> 
                             </tr> 
                           </table></td> 
                         </tr> 
                       </table></td> 
                     </tr> 
                   </table></td> 
                 </tr> 
               </table></td> 
             </tr> 
           </table> 
          </div>  
         </body>
        </html>";
    }

}