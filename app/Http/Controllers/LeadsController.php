<?php

namespace App\Http\Controllers;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Collections\CatalogElementsCollection;
use AmoCRM\Collections\ContactsCollection;
use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Collections\TagsCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\CatalogElementModel;
use AmoCRM\Models\CompanyModel;
use AmoCRM\Models\ContactModel;
use AmoCRM\Models\CustomFields\CustomFieldModel;
use AmoCRM\Models\CustomFieldsValues\BaseCustomFieldValuesModel;
use AmoCRM\Models\LeadModel;
use AmoCRM\Models\TagModel;
use App\Models\CatalogElement;
use App\Models\Company;
use App\Models\Contact;
use App\Models\ContactLead;
use App\Models\Field;
use App\Models\Lead;
use App\Models\Tag;
use League\OAuth2\Client\Token\AccessTokenInterface;

class LeadsController extends Controller
{
    public function leads()
    {
        try {
            $apiClient = $this->connect();
            $leadsService = $apiClient->leads();
            $leadsCollection = $leadsService->get();
            foreach ($leadsCollection as $lead){
                if (Lead::find($lead->getId())==null)
                    $this->addLead($lead);
            }
            echo 'Данные добавлены в БД';
        } catch (AmoCRMApiException $e) {
            printError($e);
            die;
        }

    }
    public function connect(): AmoCRMApiClient
    {
        $clientId ='6506a96f-ce69-43cf-aa6e-2fa1d74eb71e';
        $clientSecret='tYcAxNr48w9HYALMPGrI4xfuD4wMXtfd4LX3bqZBOFMQBxuN7oIMB92IMXh0K86d';
        $redirectUri='https://github.com/n7arti';
        $code = 'def502001810b5516585f5ce75cf129850dbd7d5a2876a5c59a96db570db7b7bf452de4d42b32e63093870ce4e96e3668c0336ca24c079e9c4921ca2b61e2ca2cf4644dc87bdedd10f95dbed3a797ea0ed06172d3816f20b1e21f3fc14c8d7ab862b2e6513b815e6561f5ed767b9359b54d5be47d922bb61f5e5e29f51d1654f164c465b2d133e2f17f9cf78319e534e4f8ea1d017ff5a7a557af9aa9c1efa34b62c85710c0ab3ff782adb274966c36fa4e8821eb1f904c96443327ef0ffe92bd0a3ea6f1d1660567f66300c8b8ba378675da77d5a049f1a355cfb233c583d6b1bf51ff1384a4de7c04a7ef6c47d28abd50b9a26459c8f9c07064e10effba7993b4fb9bbc488530823751332989c132ed34d95a796d810b5aa8274d27a1845efb4ff132368144931eab656e169193f98319685b8514d4f17f12dc4fe487da2a6cb7cdc4427bbb00526031c215ba748a0d4a946ee10244b08df6d4a40602ba37d071b64ca86c5601a1608ec04f99af212f14b0664d8822c24510fcd9a51fd5485e0b6da9bcba22aadcf40001d86449af5f6705b54e651736e864884abf691ae6c01292d2b85228d8d6b0b3873bcf219063ff519bea77ad6a1fc512398cb0ac1caffc0c292cd5a47d46a45d85dabb857f7d54e4810926c9c6392a0a1';

        $apiClient = new AmoCRMApiClient($clientId, $clientSecret, $redirectUri);
        $accessToken = $apiClient->getOAuthClient()->getAccessTokenByCode($_GET[$code]);

        $apiClient->setAccessToken($accessToken)
            ->setAccountBaseDomain($accessToken->getValues()['n7arti.amocrm.ru'])
            ->onAccessTokenRefresh(
                function (AccessTokenInterface $accessToken, string $baseDomain) {
                    saveToken(
                        [
                            'accessToken' => $accessToken->getToken(),
                            'refreshToken' => $accessToken->getRefreshToken(),
                            'expires' => $accessToken->getExpires(),
                            'baseDomain' => $baseDomain,
                        ]
                    );
                }
            );
        return $apiClient;
    }

    public function addLead(LeadModel $lead){
        $contacts = $lead->getContacts();
        $this->addContacts($contacts);
        $company_id = $this->addCompany($lead->getCompany());
        if($lead->getTags()!=null)
            $this->addTags($lead->getTags());
        if($lead->getCustomFieldsValues()!=null)
            $this->addFields($lead->getCustomFieldsValues());
        if($lead->getCatalogElementsLinks()!=null)
            $this->addCatalogElements($lead->getCatalogElementsLinks());

        Lead::create([
            'id' => $lead->getId(),
            'name' => $lead->getName(),
            'responsibleUserId' => $lead->getResponsibleUserId(),
            "groupId" => $lead->getGroupId(),
            'createdBy' => $lead->getCreatedBy(),
            'updatedBy' => $lead->getUpdatedBy(),
            'createdAt' => $lead->getCreatedAt(),
            'updatedAt' => $lead->getUpdatedAt(),
            'accountId' => $lead->getAccountId(),
            'pipelineId' => $lead->getPipelineId(),
            'statusId' => $lead->getStatusId(),
            'closedAt' => $lead->getClosedAt(),
            'closestTaskAt' => $lead->getClosestTaskAt(),
            'price' => $lead->getPrice(),
            'lossReasonId' => $lead->getLossReasonId(),
            'isDeleted' => $lead->getIsDeleted(),
            'sourceId' => $lead->getSourceId(),
            'sourceExternalId' => $lead->getSourceExternalId(),
            'score' => $lead->getScore(),
            'isPriceModifiedByRobot' => $lead->getIsPriceModifiedByRobot(),
            "company_id" => $company_id,
            'visitorUid' => $lead->getVisitorUid(),
            'complexRequestIds' => $lead->getComplexRequestIds(),
            'requestId' => $lead->getRequestId()
        ]);

        foreach ($contacts as $contact) {
            ContactLead::create([
                'contact_id' => $contact->getId(),
                'lead_id' => $lead->getId()
            ]);
        }

    }

    public function addContacts(ContactsCollection $contacts)
    {
        foreach ($contacts as $contact) {
            if(Contact::find($contact->getId())==null){
            Contact::create([
                'id' => $contact->getId(),
                'name' => $contact->getName(),
                "firstName" => $contact->getFirstName(),
                'lastName' => $contact->getLastName(),
                'responsibleUserId' => $contact->getResponsibleUserId(),
                'groupId' => $contact->getGroupId(),
                'createdBy' => $contact->getCreatedBy(),
                'updatedBy' => $contact->getUpdatedBy(),
                'createdAt' => $contact->getCreatedAt(),
                'updatedAt' => $contact->getUpdatedAt(),
                'closestTaskAt' => $contact->getClosestTaskAt(),
                'accountId' => $contact->getAccountId(),
                'isMain' => $contact->getIsMain(),
                'company_id' => $contact->getCompany()->getId(),
                'requestId' => $contact->getRequestId()
            ]);}
        }
    }

    public function addCompany(CompanyModel $company): int
    {
        if(Company::find($company->getId())==null){
        Company::create([
            'id' => $company->getId(),
            'name' => $company->getName(),
            'responsibleUserId' => $company->getResponsibleUserId(),
            'groupId' => $company->getGroupId(),
            'createdBy' => $company->getCreatedBy(),
            'updatedBy' => $company->getUpdatedBy(),
            'createdAt' => $company->getCreatedAt(),
            'updatedAt' => $company->getUpdatedAt(),
            'closestTaskAt' => $company->getClosestTaskAt(),
            'accountId' => $company->getAccountId(),
            'requestId' => $company->getRequestId(),
        ]);}

        return $company->getId();
    }

    public function addFields(CustomFieldsValuesCollection $fields){
        foreach ($fields as $field){
            if(Field::find($field->getId())==null){
            Field::create([
                'id' => $field->getFieldId(),
                'fieldCode' =>  $field->getFieldCode(),
                'fieldName' => $field->getFieldName()
            ]);}
        }
    }

    public function addCatalogElements(CatalogElementsCollection $elements){
        foreach ($elements as $element){
            if(CatalogElement::find($element->getId())==null){
            CatalogElement::create([
                'id' =>  $element->getId(),
                'name' =>  $element->getName(),
                'catalogId' =>  $element->getCatalogId(),
                'createdBy' =>  $element->getCreatedBy(),
                'updatedBy' =>  $element->getUpdatedBy(),
                'createdAt' =>  $element->getCreatedAt(),
                'updatedAt' =>  $element->getUpdatedAt(),
                'isDeleted' =>  $element->getIsDeleted(),
                'quantity' =>  $element->getQuantity(),
                'priceId' =>  $element->getPriceId(),
                'accountId' =>  $element->getAccountId(),
                'invoiceLink' =>  $element->getInvoiceLink(),
                'requestId' =>  $element->getRequestId(),
            ]);}
        }
    }

    public function addTags(TagsCollection $tags){
        foreach ($tags as $tag){
            if(Tag::find($tag->getId())==null){
            Tag::create([
               'id' => $tag->getId(),
               'name' => $tag->getName(),
                'color' => $tag->getColor(),
                'requestId' => $tag->getRequestId()
            ]);}
        }

    }

}
