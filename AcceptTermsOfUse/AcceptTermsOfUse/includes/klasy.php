<?php

use MediaWiki\Auth\AuthenticationRequest;
use MediaWiki\Auth\AuthManager;
use MediaWiki\Auth\AbstractSecondaryAuthenticationProvider;
use MediaWiki\Auth\AuthenticationResponse;

class MyExtraFieldsAuthenticationRequest extends AuthenticationRequest {
    // Mark this AuthenticationRequest as optional.
    public $required = self::OPTIONAL;

    /** @var string */
    public $termsChckbx;

    public function getFieldInfo() {
    global $wgAcceptTermsOfUsePage;
#default value is false in config but i cant use wfMessage in json so i use this conditional
    if ($wgAcceptTermsOfUsePage === false) {
    $discpage = '[['.wfMessage( 'disclaimerpage' ).'|'.wfMessage('akceptacja-regulaminu0').']]';
    } else {
    $discpage = $wgAcceptTermsOfUsePage;
    }
    $komunikat = wfMessage( 'akceptacja-regulaminu', $discpage );
        return [
            'termsChckbx' => [
                'type' => 'checkbox',
                'label' => $komunikat,
		'optional' => false,
            ]
        ];
    }
}

class MyExtraFieldsSecondaryAuthenticationProvider
    extends AbstractSecondaryAuthenticationProvider
{
    public function getAuthenticationRequests( $action, array $options ) {
        if ( $action === AuthManager::ACTION_CREATE ) {
            return [ new MyExtraFieldsAuthenticationRequest() ];
        }
        return [];
    }

    public function beginSecondaryAuthentication( $user, array $reqs ) {
        return AuthenticationResponse::newAbstain();
    }

    public function testForAccountCreation( $user, $creator, array $reqs ) {
        global $wgAcceptTermsOfUsePage;
        #default value is false in config but i cant use wfMessage in json so this conditional
        if ($wgAcceptTermsOfUsePage === false) {
        $discpage = '[['.wfMessage( 'disclaimerpage' ).'|'.wfMessage('akceptacja-regulaminu0').']]';
        } else {
        $discpage = $wgAcceptTermsOfUsePage;
        }
        $komunikat = wfMessage( 'musisz-zaakceptowac-regulamin', $discpage );

        $req = AuthenticationRequest::getRequestByClass( $reqs,
            MyExtraFieldsAuthenticationRequest::class );
	if ( !$req ) {
	    return StatusValue::newFatal( $komunikat );
	}
	if ( $req->termsChckbx === true ) {
	    return StatusValue::newGood();
	}
    }
    public function beginSecondaryAccountCreation( $user, $creator, array $reqs ) {
        return AuthenticationResponse::newPass();
    }
}

class MyExtraFieldsHooks {
    public static function onAuthChangeFormFields(
        array $requests, array $fieldInfo, array &$formDescriptor, $action
    ) {
        if ( isset( $formDescriptor['termsChckbx'] ) ) {
            $formDescriptor['termsChckbx']['cssclass'] = 'akceptacja-regulaminu';
        }
    }
}
