<?php

namespace Dvsa\OlcsTest\GdsVerify\Data;

use Dvsa\Olcs\GdsVerify\Data\Metadata\MatchingServiceAdapter;

/**
 * MatchingServiceAdapter test
 */
class MatchingServiceAdapterTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSigningCertificate()
    {
        $msaMetadata = $this->getSut();
        $this->assertSame(
            'MIIDXTCCAkWgAwIBAgIJAOlKkJ8iwQH3MA0GCSqGSIb3DQEBCwUAMEUxCzAJBgNV
                        BAYTAkdCMRMwEQYDVQQIDApTb21lLVN0YXRlMSEwHwYDVQQKDBhJbnRlcm5ldCBX
                        aWRnaXRzIFB0eSBMdGQwHhcNMTYwMzE3MTMxNTU1WhcNNDMwODAyMTMxNTU1WjBF
                        MQswCQYDVQQGEwJHQjETMBEGA1UECAwKU29tZS1TdGF0ZTEhMB8GA1UECgwYSW50
                        ZXJuZXQgV2lkZ2l0cyBQdHkgTHRkMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIB
                        CgKCAQEAodvk319G7TFMR5NHExFCyLF82E2yLw22a3q1AughBHCwhliDcDEgakKu
                        +qClwfampRcvxGQUViWQ7fiFAtX7U7dZ+gwvHA5QXpCoCTDjll67GgrLazuxxUMF
                        IdzFXJlL6iLuKfb9rPw6xUzVwpXrWq8hRVNhsV1K6cg/0eZm4Abh83ISlxSbJIH7
                        Eg/Ms93Y8KG6sw7qYdbtRd8dV7BOTczLmPLtwIiflR+beUNyLPeSvFwjSsSDadD4
                        OvtRuhQrg/zX8+ZeIKxJSHQBTlwne6PGfmp9ZdcYxuZGVg84AwRDrqVk83hPACRU
                        5YfhUKxeVUp3hka6A176pzxYoo/4nwIDAQABo1AwTjAdBgNVHQ4EFgQUGlYCLUl2
                        v4CfX6DUqsbVs/hhdKswHwYDVR0jBBgwFoAUGlYCLUl2v4CfX6DUqsbVs/hhdKsw
                        DAYDVR0TBAUwAwEB/zANBgkqhkiG9w0BAQsFAAOCAQEAcg/DeAs4Qv7YLiZ4Q3Qe
                        19HN7lhUoBARryCC2FBsVfKP5wVNEDGHTtdcVXdem83uDwKjq6XqoMx0Xzha3cE2
                        lMCTqSnWeB4HH3OYLnDnS0a3DwEaIKa5sMCnr5eTr1InLy7mCos4XgCo8qACDmqO
                        0kUkK2LSKiNGk3hm3mz+PM9nAETdFXHy9bWNHnTQ4xHfBFQSBCN1oFQFY0pErakj
                        TwEb7qrOF9mj4toTXouxSZpsWrOAw4q5EC+wiKwNx149SG7VLvc498VLdOOkfSHG
                        Ib8/+KdN84WLI/x0/72eRR+DhBMrtCT6DR00sBK3B/hLUSxIDGUXdRedUNr/51uC
                        6w==
                    ',
            $msaMetadata->getSigningCertificate()
        );
    }

    public function testGetSigningCertificateMissing()
    {
        $federationMetadata = $this->getSut('missing-cert-federation.xml');
        $this->setExpectedException(\Dvsa\Olcs\GdsVerify\Exception::class, 'Matching Service Adapter');
        $federationMetadata->getSigningCertificate();
    }

    /**
     * @return MatchingServiceAdapter
     */
    private function getSut($xmlFilename = 'msa-meta.xml')
    {
        $xml = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . $xmlFilename);

        return new MatchingServiceAdapter($xml);
    }
}
