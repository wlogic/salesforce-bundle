<?php
namespace Phpforce\SalesforceBundle\Command;

use Phpforce\SalesforceBundle\SoapClient\Client as SoapClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Guzzle\Http\Client;

/**
 * Fetch latest WSDL from Salesforce and store it locally
 *
 * @author David de Boer <david@ddeboer.nl>
 */
class RefreshWsdlCommand extends Command
{
    protected static $defaultName = 'phpforce:refresh-wsdl';

    protected static $defaultDescription = 'Refresh Salesforce WSDL';

    private SoapClient $client;

    private string $wsdlFile;

    public function __construct(SoapClient $client, string $wsdlFile)
    {
        $this->client = $client;
        $this->wsdlFile = $wsdlFile;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setHelp(
                'Refreshing the WSDL itself requires a WSDL, so when using this'
                . 'command for the first time, please download the WSDL '
                . 'manually from Salesforce'
            )
            ->addOption(
                'no-cache-clear',
                'c',
                InputOption::VALUE_NONE,
                'Do not clear cache after refreshing WSDL'
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Updating the WSDL file');

        // Get current session id
        $loginResult = $this->client->getLoginResult();
        $sessionId = $loginResult->getSessionId();
        $instance = $loginResult->getServerInstance();

        $url = sprintf('https://%s.salesforce.com', $instance);
        $guzzle = new Client(
            $url,
            array(
                'curl.CURLOPT_SSL_VERIFYHOST' => false,
                'curl.CURLOPT_SSL_VERIFYPEER' => false,
            )
        );

        // type=* for enterprise WSDL
        $request = $guzzle->get('/soap/wsdl.jsp?type=*');
        $request->addCookie('sid', $sessionId);
        $response = $request->send();

        $wsdl = $response->getBody();

        // Write WSDL
        file_put_contents($this->wsdlFile, $wsdl);

        // Run clear cache command
        if (!$input->getOption('no-cache-clear')) {
            $command = $this->getApplication()->find('cache:clear');

            $arguments = array(
                'command' => 'cache:clear'
            );
            $input = new ArrayInput($arguments);
            $command->run($input, $output);
        }

        return Command::SUCCESS;
    }
}

