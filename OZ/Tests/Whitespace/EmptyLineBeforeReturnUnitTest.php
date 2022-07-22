<?php

namespace OZ\Tests\Whitespace;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Class EmptyLineBeforeReturnUnitTest
 */
class EmptyLineBeforeReturnUnitTest extends AbstractSniffUnitTest
{
    /**
     * Returns the lines where errors should occur.
     *
     * @return array <int line number> => <int number of errors>
     */
    public function getErrorList()
    {
        $file = func_get_arg( 0 );

        switch( $file ) {
            case 'EmptyLineBeforeReturnUnitTest.success':
                return [];

            case 'EmptyLineBeforeReturnUnitTest.fail':
                return [
                    11 => 1,
                    24 => 1,
                    30 => 1
                ];
        }
    }

    /**
     * Returns the lines where warnings should occur.
     *
     * @return array <int line number> => <int number of warnings>
     */
    public function getWarningList(): array
    {
        return [];
    }
}
