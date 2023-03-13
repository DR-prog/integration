<?php

namespace App\Http\Controllers;

use App\Actions\RegistrationAction;
use App\Actions\TokenActions;
use App\Http\Requests\SendFormRequest;
use AmoCRM\Collections\ContactsCollection;
use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Collections\Leads\LeadsCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\ContactModel;
use AmoCRM\Models\CustomFieldsValues\MultitextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\MultitextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\MultitextCustomFieldValueModel;
use AmoCRM\Models\LeadModel;

class SendInfoController extends Controller
{
    public function sendForm(SendFormRequest $request)
    {
        $clientId = "dc443ba3-cc30-4334-a5f2-0e8226762406";
        $clientSecret = "7sP6ugyjuo30h2D53wtvViGQSuqhWQBUJjzbrJjPUf3OcdlkqHsaQtogqstn7Jrf";
        $redirectUri = "https://4d03-176-112-255-83.eu.ngrok.io/auth";
        $apiClient = RegistrationAction::registration($clientId, $clientSecret, $redirectUri);
        if (
            !file_exists(__DIR__ .'\..\..\..\TOKEN_FILE.txt') ||
            (!file_get_contents(__DIR__ . '\..\..\..\TOKEN_FILE.txt'))
        ) {
            $accessToken = TokenActions::mySaveToken();
        } else {
            $accessToken = TokenActions::myGetToken();
        }
        $apiClient->setAccessToken($accessToken);
        $data = $request->validated();
        $externalData = [
            [
                'price' => $data['price'],
                'contact' => [
                    'first_name' => $data['first_name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                ],
            ],
        ];

        $leadsCollection = new LeadsCollection();
//Создадим модели и заполним ими коллекцию
        foreach ($externalData as $externalLead) {
            $lead = (new LeadModel())
                ->setPrice($externalLead['price'])
                ->setContacts(
                    (new ContactsCollection())
                        ->add(
                            (new ContactModel())
                                ->setFirstName($externalLead['contact']['first_name'])
                                ->setCustomFieldsValues(
                                    (new CustomFieldsValuesCollection())
                                        ->add(
                                            (new MultitextCustomFieldValuesModel())
                                                ->setFieldCode('PHONE')
                                                ->setValues(
                                                    (new MultitextCustomFieldValueCollection())
                                                        ->add(
                                                            (new MultitextCustomFieldValueModel())
                                                                ->setValue($externalLead['contact']['phone'])
                                                        )
                                                )
                                        )
                                        ->add(
                                            (new MultitextCustomFieldValuesModel())
                                                ->setFieldCode('EMAIL')
                                                ->setValues(
                                                    (new MultitextCustomFieldValueCollection())
                                                        ->add(
                                                            (new MultitextCustomFieldValueModel())
                                                                ->setValue($externalLead['contact']['email'])
                                                        )
                                                )
                                        )
                                )
                        )
                );
            $leadsCollection->add($lead);
        }
//Создадим сделки
        try {
            $addedLeadsCollection = $apiClient->leads()->addComplex($leadsCollection);
        } catch (AmoCRMApiException $e) {
            printError($e);
            die;
        }
        return 'Ваше обращение было зарегистрировано';
    }

    public function showForm()
    {
        return view('form.sendForm');
    }
}
