<?php
class ServiceFaultType {
  public $StatusCode; // string
  public $Reason; // string
}

class UuidType {
}

class NotEmptyStringType {
}

class UrlType {
}

class ConsumerTransportType {
}

class ChargedAmountType {
}

class ConsumerType {
  public $Msisdn; // string
  public $Alias; // string
  public $RoutableAlias; // string
  public $Network; // NetworkType
}

class NetworkType {
  public $Name; // string
  public $Iso3166CountryCode; // string
  public $Iso4217CurrencyCode; // string
}

class TransactionStatusType {
  public $StatusCode; // StatusCode
  public $MisCode; // string
}

class StatusCode {
}

class PaymentStatusType {
  public $StatusCode; // StatusCode
  public $MisCode; // string
}

class DurationType {
  public $Length; // int
  public $Period; // Period
}

class Period {
}

class MoneyType {
  public $CurrencyCode; // CurrencyCode
  public $MonetaryValue; // MonetaryValue
}

class CurrencyCode {
}

class MonetaryValue {
}

class TransactionType {
  public $Type; // Type
}

class Type {
}

class ClassificationType {
  public $Category; // Category
  public $Adult; // boolean
}

class Category {
}

class MarketingPreferencesType {
  public $OptedInForMerchant; // boolean
  public $OptedInForMerchantPartners; // boolean
}

class DeviceType {
  public $Type; // Type
}

class OrderDirectionType {
  public $Type; // Type
}

class CountryType {
  public $Iso3166CountryCode; // string
  public $Iso4217CurrencyCode; // string
}

class IdentificationStatusType {
  public $StatusCode; // StatusCode
  public $MisCode; // NotEmptyStringType
}

class GetConsumerFromMsisdn {
  public $Msisdn; // NotEmptyStringType
}

class GetConsumerFromTransaction {
  public $TransactionId; // UuidType
}

class InitiateConsumerIdentificationSession {
  public $CallbackUrl; // UrlType
}

class GetConsumerFromIdentificationSession {
  public $IdentificationSessionId; // UuidType
}

class GetConsumerMarketingStatus {
  public $BrandName; // NotEmptyStringType
  public $ConsumerIdentity; // NotEmptyStringType
}

class OptOutConsumerFromMarketing {
  public $BrandName; // NotEmptyStringType
  public $ConsumerIdentity; // NotEmptyStringType
}

class GetEmailAddressesForMarketing {
  public $BrandName; // NotEmptyStringType
}

class GetNetworkFromIPAddress {
  public $IPAddress; // string
  public $UserAgent; // string
  public $XForwardedFor; // string
}

class GetCountryFromRequestHeaders {
  public $RemoteIPAddress; // string
  public $UserAgent; // string
  public $XForwardedFor; // string
}

class GetConsumerFromMsisdnResponse {
  public $Consumer; // ConsumerType
}

class GetConsumerFromTransactionResponse {
  public $Consumer; // ConsumerType
}

class InitiateConsumerIdentificationSessionResponse {
  public $IdentificationSessionId; // UuidType
  public $RedirectUrl; // UrlType
}

class GetConsumerFromIdentificationSessionResponse {
  public $IdentificationStatus; // IdentificationStatusType
  public $Consumer; // ConsumerType
}

class GetConsumerMarketingStatusResponse {
  public $MarketingPreferences; // MarketingPreferencesType
}

class OptOutConsumerFromMarketingResponse {
}

class GetEmailAddressesForMarketingResponse {
  public $EmailAddress; // NotEmptyStringType
}

class GetNetworkFromIPAddressResponse {
  public $Network; // NetworkType
}

class GetCountryFromRequestHeadersResponse {
  public $StatusCode; // int
  public $Country; // CountryType
}

class SubscriptionStatusType {
  public $StatusCode; // StatusCode
  public $MisCode; // NotEmptyStringType
}

class SubscriptionStatusCodeType {
  public $Type; // Type
}

class GetSubscriptionTransactionOrderByFieldType {
  public $Type; // Type
}

class BillingPeriodType {
  public $PeriodNumber; // int
  public $StartDate; // dateTime
  public $EndDate; // dateTime
}

class SubscriptionBasicInfoType {
  public $TransactionId; // UuidType
  public $BrandName; // NotEmptyStringType
  public $ProductCode; // NotEmptyStringType
  public $ProductDescription; // NotEmptyStringType
  public $Consumer; // ConsumerType
  public $SubscriptionStatus; // SubscriptionStatusType
  public $CreationDate; // dateTime
  public $ActivationDate; // dateTime
  public $FailedDate; // dateTime
  public $CancelledDate; // dateTime
  public $EndedDate; // dateTime
  public $BillingFrequency; // DurationType
  public $RecurringCost; // MoneyType
  public $LastSuccessfulChargeDate; // dateTime
  public $LastChargeAttemptDate; // dateTime
  public $CurrentChargeDueDate; // dateTime
  public $NextChargeDueDate; // dateTime
}

class PaymentInfoType {
  public $PaymentId; // UuidType
  public $Amount; // MoneyType
  public $RequestedDate; // dateTime
  public $CompletedDate; // dateTime
  public $BillingPeriodNumber; // int
  public $PaymentStatus; // PaymentStatusType
}

class InitiateSubscriptionSetup {
  public $BrandName; // NotEmptyStringType
  public $ProductCode; // ProductCode
  public $ProductDescription; // ProductDescription
  public $Classification; // ClassificationType
  public $InitialCost; // MoneyType
  public $RecurringCost; // MoneyType
  public $BillingFrequency; // DurationType
  public $WelcomeSmsDelay; // DurationType
  public $ExpiryDate; // dateTime
  public $SpendLimit; // MoneyType
  public $FreePeriod; // DurationType
  public $GracePeriod; // DurationType
  public $SuspendPeriod; // DurationType
  public $ServiceDeliveryMessage; // ServiceDeliveryMessage
  public $ReturnUrl; // UrlType
  public $FulfilmentUrl; // UrlType
  public $ReceiptSms; // boolean
  public $ConsumerIdentity; // string
  public $ConsumerTransport; // ConsumerTransportType
  public $Iso3166CountryCodeOverride; // string
}

class ServiceDeliveryMessage {
}

class GetSubscriptionTransactionInfo {
  public $TransactionId; // UuidType
}

class GetSubscriptionTransactionStatus {
  public $TransactionId; // UuidType
}

class GetSubscriptionStatus {
  public $TransactionId; // UuidType
}

class GetSubscriptionStatusForConsumer {
  public $ProductCode; // ProductCode
  public $ConsumerIdentity; // NotEmptyStringType
}

class ChargeSubscription {
  public $TransactionId; // UuidType
}

class CancelSubscription {
  public $TransactionId; // UuidType
}

class CancelSubscriptionForConsumer {
  public $ProductCode; // ProductCode
  public $ConsumerIdentity; // NotEmptyStringType
}

class GetSubscriptionTransactions {
  public $BrandName; // string
  public $ProductCode; // string
  public $ConsumerIdentity; // string
  public $SubscriptionStatus; // SubscriptionStatusCodeType
  public $OrderByField; // GetSubscriptionTransactionOrderByFieldType
  public $OrderDirection; // OrderDirectionType
  public $MaxResults; // int
  public $FirstResult; // int
}

class GetSubscriptionChargeHistory {
  public $TransactionId; // UuidType
  public $MaxResults; // int
  public $FirstResult; // int
}

class InitiateSubscriptionSetupResponse {
  public $TransactionId; // UuidType
  public $RedirectUrl; // UrlType
}

class GetSubscriptionTransactionInfoResponse {
  public $TransactionId; // UuidType
  public $TransactionStatus; // TransactionStatusType
  public $SubscriptionStatus; // SubscriptionStatusType
  public $BrandName; // NotEmptyStringType
  public $ProductCode; // ProductCode
  public $ProductDescription; // ProductDescription
  public $Classification; // ClassificationType
  public $InitialCost; // MoneyType
  public $RecurringCost; // MoneyType
  public $WelcomeSmsDelay; // DurationType
  public $BillingFrequency; // DurationType
  public $ExpiryDate; // dateTime
  public $SpendLimit; // MoneyType
  public $FreePeriod; // DurationType
  public $CurrentTotalSpending; // MoneyType
  public $BillableFrom; // dateTime
  public $CurrentBillingPeriod; // BillingPeriodType
  public $NextBillingPeriod; // BillingPeriodType
  public $Ended; // dateTime
  public $LastNotified; // dateTime
  public $LastSubmittedPaymentId; // UuidType
  public $LastSuccessfulPaymentId; // UuidType
  public $ServiceDeliveryMessage; // ServiceDeliveryMessage
  public $ReturnUrl; // UrlType
  public $FulfilmentUrl; // UrlType
  public $ReceiptSms; // boolean
  public $MarketingPreferences; // MarketingPreferencesType
  public $Consumer; // ConsumerType
}

class ProductCode {
}

class GetSubscriptionTransactionStatusResponse {
  public $TransactionStatus; // TransactionStatusType
}

class GetSubscriptionStatusResponse {
  public $SubscriptionStatus; // SubscriptionStatusType
}

class GetSubscriptionStatusForConsumerResponse {
  public $SubscriptionStatus; // SubscriptionStatusType
}

class ChargeSubscriptionResponse {
  public $PaymentId; // UuidType
  public $PaymentStatus; // PaymentStatusType
}

class CancelSubscriptionResponse {
}

class CancelSubscriptionForConsumerResponse {
}

class GetSubscriptionTransactionsResponse {
  public $totalResults; // int
  public $Subscription; // SubscriptionBasicInfoType
}

class GetSubscriptionChargeHistoryResponse {
  public $totalResults; // int
  public $Payment; // PaymentInfoType
}

class InitiateOneOffPurchase {
  public $BrandName; // NotEmptyStringType
  public $ProductCode; // ProductCode
  public $ProductDescription; // ProductDescription
  public $Classification; // ClassificationType
  public $Cost; // MoneyType
  public $DiscountCost; // MoneyType
  public $ServiceDeliveryMessage; // ServiceDeliveryMessage
  public $ReturnUrl; // UrlType
  public $FulfilmentUrl; // UrlType
  public $ReceiptSms; // boolean
  public $ConsumerIdentity; // string
  public $ConsumerTransport; // ConsumerTransportType
  public $Iso3166CountryCodeOverride; // string
}


class GetOneOffPurchaseTransactionInfo {
  public $TransactionId; // UuidType
}

class GetOneOffPurchaseTransactionStatus {
  public $TransactionId; // UuidType
}

class GetOneOffPurchasePaymentStatus {
  public $PaymentId; // UuidType
}

class GetOneOffPurchaseTransactionPaymentStatus {
  public $TransactionId; // UuidType
}

class InitiateOneOffPurchaseResponse {
  public $TransactionId; // UuidType
  public $PaymentId; // UuidType
  public $DiscountPaymentId; // UuidType
  public $RedirectUrl; // UrlType
}

class GetOneOffPurchaseTransactionInfoResponse {
  public $TransactionId; // UuidType
  public $TransactionStatus; // TransactionStatusType
  public $PaymentId; // UuidType
  public $PaymentStatus; // PaymentStatusType
  public $BrandName; // NotEmptyStringType
  public $ProductCode; // ProductCode
  public $ProductDescription; // ProductDescription
  public $Classification; // ClassificationType
  public $Cost; // MoneyType
  public $ServiceDeliveryMessage; // ServiceDeliveryMessage
  public $ReturnUrl; // UrlType
  public $FulfilmentUrl; // UrlType
  public $ReceiptSms; // boolean
  public $MarketingPreferences; // MarketingPreferencesType
  public $Consumer; // ConsumerType
}

class GetOneOffPurchaseTransactionStatusResponse {
  public $TransactionStatus; // TransactionStatusType
}

class GetOneOffPurchasePaymentStatusResponse {
  public $PaymentStatus; // PaymentStatusType
}

class GetOneOffPurchaseTransactionPaymentStatusResponse {
  public $TransactionPaymentStatus; // PaymentStatusType
  public $ChargedAmount; // ChargedAmountType
  public $ChargedPaymentId; // UuidType
}

class DirectCharge {
  public $BrandName; // NotEmptyStringType
  public $ProductCode; // ProductCode
  public $ProductDescription; // ProductDescription
  public $Classification; // ClassificationType
  public $Cost; // MoneyType
  public $ReceiptSms; // boolean
  public $ConsumerIdentity; // string
  public $IdentificationSessionId; // UuidType
  public $ChargeFrequency; // DurationType
}

class GetDirectChargeTransactionInfo {
  public $TransactionId; // UuidType
}

class GetDirectChargeTransactionStatus {
  public $TransactionId; // UuidType
}

class GetDirectChargePaymentStatus {
  public $PaymentId; // UuidType
}

class DirectChargeResponse {
  public $TransactionId; // UuidType
  public $PaymentId; // UuidType
  public $PaymentStatus; // PaymentStatusType
}

class GetDirectChargeTransactionInfoResponse {
  public $TransactionId; // UuidType
  public $TransactionType; // TransactionType
  public $TransactionStatus; // TransactionStatusType
  public $PaymentId; // UuidType
  public $PaymentStatus; // PaymentStatusType
  public $BrandName; // NotEmptyStringType
  public $ProductCode; // ProductCode
  public $ProductDescription; // ProductDescription
  public $Classification; // ClassificationType
  public $Cost; // MoneyType
  public $ReceiptSms; // boolean
  public $Consumer; // ConsumerType
}

class ProductDescription {
}

class GetDirectChargeTransactionStatusResponse {
  public $TransactionStatus; // TransactionStatusType
}

class GetDirectChargePaymentStatusResponse {
  public $PaymentStatus; // PaymentStatusType
}

class InitiateSingleClickOneOffPurchase {
  public $BrandName; // NotEmptyStringType
  public $ProductCode; // ProductCode
  public $ProductDescription; // ProductDescription
  public $Classification; // ClassificationType
  public $Cost; // MoneyType
  public $DiscountCost; // MoneyType
  public $ServiceDeliveryMessage; // ServiceDeliveryMessage
  public $ReturnUrl; // UrlType
  public $FulfilmentUrl; // UrlType
  public $ReceiptSms; // boolean
  public $ConsumerIdentity; // string
  public $ConsumerTransport; // ConsumerTransportType
  public $Iso3166CountryCodeOverride; // string
}

class GetConsumerSingleClickStatus {
  public $BrandName; // NotEmptyStringType
  public $ConsumerIdentity; // NotEmptyStringType
}

class GetSingleClickSignUpPage {
  public $BrandName; // NotEmptyStringType
  public $ReturnUrl; // UrlType
  public $ReturnMessage; // NotEmptyStringType
}

class GetSingleClickTransactionInfo {
  public $TransactionId; // UuidType
}

class GetSingleClickTransactionStatus {
  public $TransactionId; // UuidType
}

class GetSingleClickPaymentStatus {
  public $PaymentId; // UuidType
}

class InitiateSingleClickOneOffPurchaseResponse {
  public $TransactionId; // UuidType
  public $PaymentId; // UuidType
  public $DiscountPaymentId; // UuidType
  public $Markup; // NotEmptyStringType
}

class GetConsumerSingleClickStatusResponse {
  public $OptedIn; // boolean
}

class GetSingleClickSignUpPageResponse {
  public $SingleClickId; // UuidType
  public $SignUpPageUrl; // UrlType
}

class GetSingleClickTransactionInfoResponse {
  public $TransactionId; // UuidType
  public $TransactionStatus; // TransactionStatusType
  public $PaymentId; // UuidType
  public $PaymentStatus; // PaymentStatusType
  public $BrandName; // NotEmptyStringType
  public $ProductCode; // ProductCode
  public $ProductDescription; // ProductDescription
  public $Classification; // ClassificationType
  public $Cost; // MoneyType
  public $ServiceDeliveryMessage; // ServiceDeliveryMessage
  public $ReturnUrl; // UrlType
  public $FulfilmentUrl; // UrlType
  public $ReceiptSms; // boolean
  public $MarketingPreferences; // MarketingPreferencesType
  public $Consumer; // ConsumerType
}

class GetSingleClickTransactionStatusResponse {
  public $TransactionStatus; // TransactionStatusType
}

class GetSingleClickPaymentStatusResponse {
  public $PaymentStatus; // PaymentStatusType
}

class GetSingleClickTransactionPaymentStatus {
  public $TransactionId; // UuidType
}

class GetSingleClickTransactionPaymentStatusResponse {
  public $TransactionPaymentStatus; // PaymentStatusType
  public $ChargedAmount; // ChargedAmountType
  public $ChargedPaymentId; // UuidType
}

class ChargingProviderType {
  public $Name; // string
  public $ChargeType; // ChargeType
  public $Iso3166CountryCode; // string
}

class ChargeType {
  public $Type; // Type
}

class PricePointType {
  public $CurrencyCode; // string
  public $MonetaryValue; // MonetaryValue
  public $Mulitplicity; // int
  public $NonAdultUse; // boolean
  public $AdultUse; // boolean
  public $DirectBill; // boolean
  public $PremiumSMS; // boolean
}

class PriceRangeType {
  public $CurrencyCode; // string
  public $MinimumMonetaryValue; // MinimumMonetaryValue
  public $MaximumMonetaryValue; // MaximumMonetaryValue
  public $NonAdultUse; // boolean
  public $AdultUse; // boolean
}

class MinimumMonetaryValue {
}

class MaximumMonetaryValue {
}

class SupportedPricesType {
  public $ChargingProvider; // ChargingProviderType
  public $PricePoint; // PricePointType
  public $PriceRange; // PriceRangeType
}

class GetSupportedPrices {
  public $BrandName; // NotEmptyStringType
  public $Iso3166CountryCode; // string
  public $TransactionType; // TransactionType
  public $DeviceType; // DeviceType
}

class GetSupportedPricesResponse {
  public $SupportedPrices; // SupportedPricesType
}

class PropertyType {
  public $Name; // string
  public $Value; // string
}

class GetBrands {
  public $BrandName; // string
}

class GetBrandsResponse {
  public $BrandName; // string
}

class GetBrandProperty {
  public $BrandName; // string
  public $PropertyName; // string
}

class GetBrandPropertyResponse {
  public $BrandProperty; // PropertyType
}

class UpdateBrandSmsTemplate {
  public $BrandName; // string
  public $BrandProperty; // PropertyType
}

class UpdateBrandSmsTemplateResponse {
  public $UpdatedPropertyName; // string
}


/**
 * MobpayService class
 * 
 *  
 * 
 * @author    {author}
 * @copyright {copyright}
 * @package   {package}
 */
class MobpayService extends SoapClient {

  private static $classmap = array(
                                    'ServiceFaultType' => 'ServiceFaultType',
                                    'UuidType' => 'UuidType',
                                    'NotEmptyStringType' => 'NotEmptyStringType',
                                    'UrlType' => 'UrlType',
                                    'ConsumerTransportType' => 'ConsumerTransportType',
                                    'ChargedAmountType' => 'ChargedAmountType',
                                    'ConsumerType' => 'ConsumerType',
                                    'NetworkType' => 'NetworkType',
                                    'TransactionStatusType' => 'TransactionStatusType',
                                    'StatusCode' => 'StatusCode',
                                    'PaymentStatusType' => 'PaymentStatusType',
                                    'StatusCode' => 'StatusCode',
                                    'DurationType' => 'DurationType',
                                    'Period' => 'Period',
                                    'MoneyType' => 'MoneyType',
                                    'CurrencyCode' => 'CurrencyCode',
                                    'MonetaryValue' => 'MonetaryValue',
                                    'TransactionType' => 'TransactionType',
                                    'Type' => 'Type',
                                    'ClassificationType' => 'ClassificationType',
                                    'Category' => 'Category',
                                    'MarketingPreferencesType' => 'MarketingPreferencesType',
                                    'DeviceType' => 'DeviceType',
                                    'Type' => 'Type',
                                    'OrderDirectionType' => 'OrderDirectionType',
                                    'Type' => 'Type',
                                    'CountryType' => 'CountryType',
                                    'IdentificationStatusType' => 'IdentificationStatusType',
                                    'StatusCode' => 'StatusCode',
                                    'GetConsumerFromMsisdn' => 'GetConsumerFromMsisdn',
                                    'GetConsumerFromTransaction' => 'GetConsumerFromTransaction',
                                    'InitiateConsumerIdentificationSession' => 'InitiateConsumerIdentificationSession',
                                    'GetConsumerFromIdentificationSession' => 'GetConsumerFromIdentificationSession',
                                    'GetConsumerMarketingStatus' => 'GetConsumerMarketingStatus',
                                    'OptOutConsumerFromMarketing' => 'OptOutConsumerFromMarketing',
                                    'GetEmailAddressesForMarketing' => 'GetEmailAddressesForMarketing',
                                    'GetNetworkFromIPAddress' => 'GetNetworkFromIPAddress',
                                    'GetCountryFromRequestHeaders' => 'GetCountryFromRequestHeaders',
                                    'GetConsumerFromMsisdnResponse' => 'GetConsumerFromMsisdnResponse',
                                    'GetConsumerFromTransactionResponse' => 'GetConsumerFromTransactionResponse',
                                    'InitiateConsumerIdentificationSessionResponse' => 'InitiateConsumerIdentificationSessionResponse',
                                    'GetConsumerFromIdentificationSessionResponse' => 'GetConsumerFromIdentificationSessionResponse',
                                    'GetConsumerMarketingStatusResponse' => 'GetConsumerMarketingStatusResponse',
                                    'OptOutConsumerFromMarketingResponse' => 'OptOutConsumerFromMarketingResponse',
                                    'GetEmailAddressesForMarketingResponse' => 'GetEmailAddressesForMarketingResponse',
                                    'GetNetworkFromIPAddressResponse' => 'GetNetworkFromIPAddressResponse',
                                    'GetCountryFromRequestHeadersResponse' => 'GetCountryFromRequestHeadersResponse',
                                    'SubscriptionStatusType' => 'SubscriptionStatusType',
                                    'StatusCode' => 'StatusCode',
                                    'SubscriptionStatusCodeType' => 'SubscriptionStatusCodeType',
                                    'Type' => 'Type',
                                    'GetSubscriptionTransactionOrderByFieldType' => 'GetSubscriptionTransactionOrderByFieldType',
                                    'Type' => 'Type',
                                    'BillingPeriodType' => 'BillingPeriodType',
                                    'SubscriptionBasicInfoType' => 'SubscriptionBasicInfoType',
                                    'PaymentInfoType' => 'PaymentInfoType',
                                    'InitiateSubscriptionSetup' => 'InitiateSubscriptionSetup',
                                    'ProductCode' => 'ProductCode',
                                    'ProductDescription' => 'ProductDescription',
                                    'ServiceDeliveryMessage' => 'ServiceDeliveryMessage',
                                    'GetSubscriptionTransactionInfo' => 'GetSubscriptionTransactionInfo',
                                    'GetSubscriptionTransactionStatus' => 'GetSubscriptionTransactionStatus',
                                    'GetSubscriptionStatus' => 'GetSubscriptionStatus',
                                    'GetSubscriptionStatusForConsumer' => 'GetSubscriptionStatusForConsumer',
                                    'ProductCode' => 'ProductCode',
                                    'ChargeSubscription' => 'ChargeSubscription',
                                    'CancelSubscription' => 'CancelSubscription',
                                    'CancelSubscriptionForConsumer' => 'CancelSubscriptionForConsumer',
                                    'ProductCode' => 'ProductCode',
                                    'GetSubscriptionTransactions' => 'GetSubscriptionTransactions',
                                    'GetSubscriptionChargeHistory' => 'GetSubscriptionChargeHistory',
                                    'InitiateSubscriptionSetupResponse' => 'InitiateSubscriptionSetupResponse',
                                    'GetSubscriptionTransactionInfoResponse' => 'GetSubscriptionTransactionInfoResponse',
                                    'ProductCode' => 'ProductCode',
                                    'ProductDescription' => 'ProductDescription',
                                    'ServiceDeliveryMessage' => 'ServiceDeliveryMessage',
                                    'GetSubscriptionTransactionStatusResponse' => 'GetSubscriptionTransactionStatusResponse',
                                    'GetSubscriptionStatusResponse' => 'GetSubscriptionStatusResponse',
                                    'GetSubscriptionStatusForConsumerResponse' => 'GetSubscriptionStatusForConsumerResponse',
                                    'ChargeSubscriptionResponse' => 'ChargeSubscriptionResponse',
                                    'CancelSubscriptionResponse' => 'CancelSubscriptionResponse',
                                    'CancelSubscriptionForConsumerResponse' => 'CancelSubscriptionForConsumerResponse',
                                    'GetSubscriptionTransactionsResponse' => 'GetSubscriptionTransactionsResponse',
                                    'GetSubscriptionChargeHistoryResponse' => 'GetSubscriptionChargeHistoryResponse',
                                    'InitiateOneOffPurchase' => 'InitiateOneOffPurchase',
                                    'ProductCode' => 'ProductCode',
                                    'ProductDescription' => 'ProductDescription',
                                    'ServiceDeliveryMessage' => 'ServiceDeliveryMessage',
                                    'GetOneOffPurchaseTransactionInfo' => 'GetOneOffPurchaseTransactionInfo',
                                    'GetOneOffPurchaseTransactionStatus' => 'GetOneOffPurchaseTransactionStatus',
                                    'GetOneOffPurchasePaymentStatus' => 'GetOneOffPurchasePaymentStatus',
                                    'GetOneOffPurchaseTransactionPaymentStatus' => 'GetOneOffPurchaseTransactionPaymentStatus',
                                    'InitiateOneOffPurchaseResponse' => 'InitiateOneOffPurchaseResponse',
                                    'GetOneOffPurchaseTransactionInfoResponse' => 'GetOneOffPurchaseTransactionInfoResponse',
                                    'ProductCode' => 'ProductCode',
                                    'ProductDescription' => 'ProductDescription',
                                    'ServiceDeliveryMessage' => 'ServiceDeliveryMessage',
                                    'GetOneOffPurchaseTransactionStatusResponse' => 'GetOneOffPurchaseTransactionStatusResponse',
                                    'GetOneOffPurchasePaymentStatusResponse' => 'GetOneOffPurchasePaymentStatusResponse',
                                    'GetOneOffPurchaseTransactionPaymentStatusResponse' => 'GetOneOffPurchaseTransactionPaymentStatusResponse',
                                    'DirectCharge' => 'DirectCharge',
                                    'ProductCode' => 'ProductCode',
                                    'ProductDescription' => 'ProductDescription',
                                    'GetDirectChargeTransactionInfo' => 'GetDirectChargeTransactionInfo',
                                    'GetDirectChargeTransactionStatus' => 'GetDirectChargeTransactionStatus',
                                    'GetDirectChargePaymentStatus' => 'GetDirectChargePaymentStatus',
                                    'DirectChargeResponse' => 'DirectChargeResponse',
                                    'GetDirectChargeTransactionInfoResponse' => 'GetDirectChargeTransactionInfoResponse',
                                    'ProductCode' => 'ProductCode',
                                    'ProductDescription' => 'ProductDescription',
                                    'GetDirectChargeTransactionStatusResponse' => 'GetDirectChargeTransactionStatusResponse',
                                    'GetDirectChargePaymentStatusResponse' => 'GetDirectChargePaymentStatusResponse',
                                    'InitiateSingleClickOneOffPurchase' => 'InitiateSingleClickOneOffPurchase',
                                    'ProductCode' => 'ProductCode',
                                    'ProductDescription' => 'ProductDescription',
                                    'ServiceDeliveryMessage' => 'ServiceDeliveryMessage',
                                    'GetConsumerSingleClickStatus' => 'GetConsumerSingleClickStatus',
                                    'GetSingleClickSignUpPage' => 'GetSingleClickSignUpPage',
                                    'GetSingleClickTransactionInfo' => 'GetSingleClickTransactionInfo',
                                    'GetSingleClickTransactionStatus' => 'GetSingleClickTransactionStatus',
                                    'GetSingleClickPaymentStatus' => 'GetSingleClickPaymentStatus',
                                    'InitiateSingleClickOneOffPurchaseResponse' => 'InitiateSingleClickOneOffPurchaseResponse',
                                    'GetConsumerSingleClickStatusResponse' => 'GetConsumerSingleClickStatusResponse',
                                    'GetSingleClickSignUpPageResponse' => 'GetSingleClickSignUpPageResponse',
                                    'GetSingleClickTransactionInfoResponse' => 'GetSingleClickTransactionInfoResponse',
                                    'ProductCode' => 'ProductCode',
                                    'ProductDescription' => 'ProductDescription',
                                    'ServiceDeliveryMessage' => 'ServiceDeliveryMessage',
                                    'GetSingleClickTransactionStatusResponse' => 'GetSingleClickTransactionStatusResponse',
                                    'GetSingleClickPaymentStatusResponse' => 'GetSingleClickPaymentStatusResponse',
                                    'GetSingleClickTransactionPaymentStatus' => 'GetSingleClickTransactionPaymentStatus',
                                    'GetSingleClickTransactionPaymentStatusResponse' => 'GetSingleClickTransactionPaymentStatusResponse',
                                    'ChargingProviderType' => 'ChargingProviderType',
                                    'ChargeType' => 'ChargeType',
                                    'Type' => 'Type',
                                    'PricePointType' => 'PricePointType',
                                    'MonetaryValue' => 'MonetaryValue',
                                    'PriceRangeType' => 'PriceRangeType',
                                    'MinimumMonetaryValue' => 'MinimumMonetaryValue',
                                    'MaximumMonetaryValue' => 'MaximumMonetaryValue',
                                    'SupportedPricesType' => 'SupportedPricesType',
                                    'GetSupportedPrices' => 'GetSupportedPrices',
                                    'GetSupportedPricesResponse' => 'GetSupportedPricesResponse',
                                    'PropertyType' => 'PropertyType',
                                    'GetBrands' => 'GetBrands',
                                    'GetBrandsResponse' => 'GetBrandsResponse',
                                    'GetBrandProperty' => 'GetBrandProperty',
                                    'GetBrandPropertyResponse' => 'GetBrandPropertyResponse',
                                    'UpdateBrandSmsTemplate' => 'UpdateBrandSmsTemplate',
                                    'UpdateBrandSmsTemplateResponse' => 'UpdateBrandSmsTemplateResponse',
                                   );

  public function MobpayService($wsdl = "http://browseandbuy.dialogue.net/mobpay-services/mobpay/mobpay.wsdl", $options = array()) {
    foreach(self::$classmap as $key => $value) {
      if(!isset($options['classmap'][$key])) {
        $options['classmap'][$key] = $value;
      }
    }
    parent::__construct($wsdl, $options);
  }

  /**
   *  
   *
   * @param GetConsumerFromTransaction $parameters
   * @return GetConsumerFromTransactionResponse
   */
  public function GetConsumerFromTransaction(GetConsumerFromTransaction $parameters) {
    return $this->__soapCall('GetConsumerFromTransaction', array($parameters),       array(
            'uri' => 'http://www.dialogue.net/mobpay',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param GetConsumerFromIdentificationSession $parameters
   * @return GetConsumerFromIdentificationSessionResponse
   */
  public function GetConsumerFromIdentificationSession(GetConsumerFromIdentificationSession $parameters) {
    return $this->__soapCall('GetConsumerFromIdentificationSession', array($parameters),       array(
            'uri' => 'http://www.dialogue.net/mobpay',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param GetConsumerFromMsisdn $parameters
   * @return GetConsumerFromMsisdnResponse
   */
  public function GetConsumerFromMsisdn(GetConsumerFromMsisdn $parameters) {
    return $this->__soapCall('GetConsumerFromMsisdn', array($parameters),       array(
            'uri' => 'http://www.dialogue.net/mobpay',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param InitiateConsumerIdentificationSession $parameters
   * @return InitiateConsumerIdentificationSessionResponse
   */
  public function InitiateConsumerIdentificationSession(InitiateConsumerIdentificationSession $parameters) {
    return $this->__soapCall('InitiateConsumerIdentificationSession', array($parameters),       array(
            'uri' => 'http://www.dialogue.net/mobpay',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param GetConsumerMarketingStatus $parameters
   * @return GetConsumerMarketingStatusResponse
   */
  public function GetConsumerMarketingStatus(GetConsumerMarketingStatus $parameters) {
    return $this->__soapCall('GetConsumerMarketingStatus', array($parameters),       array(
            'uri' => 'http://www.dialogue.net/mobpay',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param OptOutConsumerFromMarketing $parameters
   * @return OptOutConsumerFromMarketingResponse
   */
  public function OptOutConsumerFromMarketing(OptOutConsumerFromMarketing $parameters) {
    return $this->__soapCall('OptOutConsumerFromMarketing', array($parameters),       array(
            'uri' => 'http://www.dialogue.net/mobpay',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param GetEmailAddressesForMarketing $parameters
   * @return GetEmailAddressesForMarketingResponse
   */
  public function GetEmailAddressesForMarketing(GetEmailAddressesForMarketing $parameters) {
    return $this->__soapCall('GetEmailAddressesForMarketing', array($parameters),       array(
            'uri' => 'http://www.dialogue.net/mobpay',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param GetNetworkFromIPAddress $parameters
   * @return GetNetworkFromIPAddressResponse
   */
  public function GetNetworkFromIPAddress(GetNetworkFromIPAddress $parameters) {
    return $this->__soapCall('GetNetworkFromIPAddress', array($parameters),       array(
            'uri' => 'http://www.dialogue.net/mobpay',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param GetCountryFromRequestHeaders $parameters
   * @return GetCountryFromRequestHeadersResponse
   */
  public function GetCountryFromRequestHeaders(GetCountryFromRequestHeaders $parameters) {
    return $this->__soapCall('GetCountryFromRequestHeaders', array($parameters),       array(
            'uri' => 'http://www.dialogue.net/mobpay',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param InitiateSubscriptionSetup $parameters
   * @return InitiateSubscriptionSetupResponse
   */
  public function InitiateSubscriptionSetup(InitiateSubscriptionSetup $parameters) {
    return $this->__soapCall('InitiateSubscriptionSetup', array($parameters),       array(
            'uri' => 'http://www.dialogue.net/mobpay',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param ChargeSubscription $parameters
   * @return ChargeSubscriptionResponse
   */
  public function ChargeSubscription(ChargeSubscription $parameters) {
    return $this->__soapCall('ChargeSubscription', array($parameters),       array(
            'uri' => 'http://www.dialogue.net/mobpay',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param GetSubscriptionTransactionStatus $parameters
   * @return GetSubscriptionTransactionStatusResponse
   */
  public function GetSubscriptionTransactionStatus(GetSubscriptionTransactionStatus $parameters) {
    return $this->__soapCall('GetSubscriptionTransactionStatus', array($parameters),       array(
            'uri' => 'http://www.dialogue.net/mobpay',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param GetSubscriptionStatusForConsumer $parameters
   * @return GetSubscriptionStatusForConsumerResponse
   */
  public function GetSubscriptionStatusForConsumer(GetSubscriptionStatusForConsumer $parameters) {
    return $this->__soapCall('GetSubscriptionStatusForConsumer', array($parameters),       array(
            'uri' => 'http://www.dialogue.net/mobpay',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param CancelSubscription $parameters
   * @return CancelSubscriptionResponse
   */
  public function CancelSubscription(CancelSubscription $parameters) {
    return $this->__soapCall('CancelSubscription', array($parameters),       array(
            'uri' => 'http://www.dialogue.net/mobpay',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param GetSubscriptionStatus $parameters
   * @return GetSubscriptionStatusResponse
   */
  public function GetSubscriptionStatus(GetSubscriptionStatus $parameters) {
    return $this->__soapCall('GetSubscriptionStatus', array($parameters),       array(
            'uri' => 'http://www.dialogue.net/mobpay',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param CancelSubscriptionForConsumer $parameters
   * @return CancelSubscriptionForConsumerResponse
   */
  public function CancelSubscriptionForConsumer(CancelSubscriptionForConsumer $parameters) {
    return $this->__soapCall('CancelSubscriptionForConsumer', array($parameters),       array(
            'uri' => 'http://www.dialogue.net/mobpay',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param GetSubscriptionTransactionInfo $parameters
   * @return GetSubscriptionTransactionInfoResponse
   */
  public function GetSubscriptionTransactionInfo(GetSubscriptionTransactionInfo $parameters) {
    return $this->__soapCall('GetSubscriptionTransactionInfo', array($parameters),       array(
            'uri' => 'http://www.dialogue.net/mobpay',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param GetSubscriptionTransactions $parameters
   * @return GetSubscriptionTransactionsResponse
   */
  public function GetSubscriptionTransactions(GetSubscriptionTransactions $parameters) {
    return $this->__soapCall('GetSubscriptionTransactions', array($parameters),       array(
            'uri' => 'http://www.dialogue.net/mobpay',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param GetSubscriptionChargeHistory $parameters
   * @return GetSubscriptionChargeHistoryResponse
   */
  public function GetSubscriptionChargeHistory(GetSubscriptionChargeHistory $parameters) {
    return $this->__soapCall('GetSubscriptionChargeHistory', array($parameters),       array(
            'uri' => 'http://www.dialogue.net/mobpay',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param InitiateOneOffPurchase $parameters
   * @return InitiateOneOffPurchaseResponse
   */
  public function InitiateOneOffPurchase(InitiateOneOffPurchase $parameters) {
    return $this->__soapCall('InitiateOneOffPurchase', array($parameters),       array(
            'uri' => 'http://www.dialogue.net/mobpay',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param GetOneOffPurchaseTransactionStatus $parameters
   * @return GetOneOffPurchaseTransactionStatusResponse
   */
  public function GetOneOffPurchaseTransactionStatus(GetOneOffPurchaseTransactionStatus $parameters) {
    return $this->__soapCall('GetOneOffPurchaseTransactionStatus', array($parameters),       array(
            'uri' => 'http://www.dialogue.net/mobpay',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param GetOneOffPurchasePaymentStatus $parameters
   * @return GetOneOffPurchasePaymentStatusResponse
   */
  public function GetOneOffPurchasePaymentStatus(GetOneOffPurchasePaymentStatus $parameters) {
    return $this->__soapCall('GetOneOffPurchasePaymentStatus', array($parameters),       array(
            'uri' => 'http://www.dialogue.net/mobpay',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param GetOneOffPurchaseTransactionInfo $parameters
   * @return GetOneOffPurchaseTransactionInfoResponse
   */
  public function GetOneOffPurchaseTransactionInfo(GetOneOffPurchaseTransactionInfo $parameters) {
    return $this->__soapCall('GetOneOffPurchaseTransactionInfo', array($parameters),       array(
            'uri' => 'http://www.dialogue.net/mobpay',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param GetOneOffPurchaseTransactionPaymentStatus $parameters
   * @return GetOneOffPurchaseTransactionPaymentStatusResponse
   */
  public function GetOneOffPurchaseTransactionPaymentStatus(GetOneOffPurchaseTransactionPaymentStatus $parameters) {
    return $this->__soapCall('GetOneOffPurchaseTransactionPaymentStatus', array($parameters),       array(
            'uri' => 'http://www.dialogue.net/mobpay',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param DirectCharge $parameters
   * @return DirectChargeResponse
   */
  public function DirectCharge(DirectCharge $parameters) {
    return $this->__soapCall('DirectCharge', array($parameters),       array(
            'uri' => 'http://www.dialogue.net/mobpay',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param GetDirectChargeTransactionStatus $parameters
   * @return GetDirectChargeTransactionStatusResponse
   */
  public function GetDirectChargeTransactionStatus(GetDirectChargeTransactionStatus $parameters) {
    return $this->__soapCall('GetDirectChargeTransactionStatus', array($parameters),       array(
            'uri' => 'http://www.dialogue.net/mobpay',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param GetDirectChargePaymentStatus $parameters
   * @return GetDirectChargePaymentStatusResponse
   */
  public function GetDirectChargePaymentStatus(GetDirectChargePaymentStatus $parameters) {
    return $this->__soapCall('GetDirectChargePaymentStatus', array($parameters),       array(
            'uri' => 'http://www.dialogue.net/mobpay',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param GetDirectChargeTransactionInfo $parameters
   * @return GetDirectChargeTransactionInfoResponse
   */
  public function GetDirectChargeTransactionInfo(GetDirectChargeTransactionInfo $parameters) {
    return $this->__soapCall('GetDirectChargeTransactionInfo', array($parameters),       array(
            'uri' => 'http://www.dialogue.net/mobpay',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param InitiateSingleClickOneOffPurchase $parameters
   * @return InitiateSingleClickOneOffPurchaseResponse
   */
  public function InitiateSingleClickOneOffPurchase(InitiateSingleClickOneOffPurchase $parameters) {
    return $this->__soapCall('InitiateSingleClickOneOffPurchase', array($parameters),       array(
            'uri' => 'http://www.dialogue.net/mobpay',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param GetSingleClickSignUpPage $parameters
   * @return GetSingleClickSignUpPageResponse
   */
  public function GetSingleClickSignUpPage(GetSingleClickSignUpPage $parameters) {
    return $this->__soapCall('GetSingleClickSignUpPage', array($parameters),       array(
            'uri' => 'http://www.dialogue.net/mobpay',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param GetConsumerSingleClickStatus $parameters
   * @return GetConsumerSingleClickStatusResponse
   */
  public function GetConsumerSingleClickStatus(GetConsumerSingleClickStatus $parameters) {
    return $this->__soapCall('GetConsumerSingleClickStatus', array($parameters),       array(
            'uri' => 'http://www.dialogue.net/mobpay',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param GetSingleClickTransactionStatus $parameters
   * @return GetSingleClickTransactionStatusResponse
   */
  public function GetSingleClickTransactionStatus(GetSingleClickTransactionStatus $parameters) {
    return $this->__soapCall('GetSingleClickTransactionStatus', array($parameters),       array(
            'uri' => 'http://www.dialogue.net/mobpay',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param GetSingleClickPaymentStatus $parameters
   * @return GetSingleClickPaymentStatusResponse
   */
  public function GetSingleClickPaymentStatus(GetSingleClickPaymentStatus $parameters) {
    return $this->__soapCall('GetSingleClickPaymentStatus', array($parameters),       array(
            'uri' => 'http://www.dialogue.net/mobpay',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param GetSingleClickTransactionInfo $parameters
   * @return GetSingleClickTransactionInfoResponse
   */
  public function GetSingleClickTransactionInfo(GetSingleClickTransactionInfo $parameters) {
    return $this->__soapCall('GetSingleClickTransactionInfo', array($parameters),       array(
            'uri' => 'http://www.dialogue.net/mobpay',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param GetSingleClickTransactionPaymentStatus $parameters
   * @return GetSingleClickTransactionPaymentStatusResponse
   */
  public function GetSingleClickTransactionPaymentStatus(GetSingleClickTransactionPaymentStatus $parameters) {
    return $this->__soapCall('GetSingleClickTransactionPaymentStatus', array($parameters),       array(
            'uri' => 'http://www.dialogue.net/mobpay',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param GetSupportedPrices $parameters
   * @return GetSupportedPricesResponse
   */
  public function GetSupportedPrices(GetSupportedPrices $parameters) {
    return $this->__soapCall('GetSupportedPrices', array($parameters),       array(
            'uri' => 'http://www.dialogue.net/mobpay',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param GetBrands $parameters
   * @return GetBrandsResponse
   */
  public function GetBrands(GetBrands $parameters) {
    return $this->__soapCall('GetBrands', array($parameters),       array(
            'uri' => 'http://www.dialogue.net/mobpay',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param GetBrandProperty $parameters
   * @return GetBrandPropertyResponse
   */
  public function GetBrandProperty(GetBrandProperty $parameters) {
    return $this->__soapCall('GetBrandProperty', array($parameters),       array(
            'uri' => 'http://www.dialogue.net/mobpay',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param UpdateBrandSmsTemplate $parameters
   * @return UpdateBrandSmsTemplateResponse
   */
  public function UpdateBrandSmsTemplate(UpdateBrandSmsTemplate $parameters) {
    return $this->__soapCall('UpdateBrandSmsTemplate', array($parameters),       array(
            'uri' => 'http://www.dialogue.net/mobpay',
            'soapaction' => ''
           )
      );
  }

}

?>
