<?php
/**
 * File containing the DeleteSubtree class.
 *
 * @copyright Copyright (C) 2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */
namespace EzSystems\CookbookBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class DeleteSubtree extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName( 'ezpublish:cookbook:delete_subtree' )->setDefinition(
            array(
                new InputArgument( 'location_id', InputArgument::REQUIRED, 'The subtree\'s root Location ID' )
            )
        );
    }

    protected function execute( InputInterface $input, OutputInterface $output )
    {
        /** @var $repository \eZ\Publish\API\Repository\Repository */
        $repository = $this->getContainer()->get( 'ezpublish.api.repository' );
        $locationService = $repository->getLocationService();

        $repository->setCurrentUser( $repository->getUserService()->loadUser( 14 ) );

        $locationId = $input->getArgument( 'location_id' );

        try
        {
            // We first try to load the location so that a NotFoundException is thrown if the Location doesn't exist
            $locationService->loadLocation( $locationId );
            $locationService->deleteLocation( $locationId );
        }
        catch ( \eZ\Publish\API\Repository\Exceptions\NotFoundException $e )
        {
            $output->writeln( "No location with id $locationId" );
        }
    }
}


