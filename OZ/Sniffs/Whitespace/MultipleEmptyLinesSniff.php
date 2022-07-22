<?php
/**
 * Check multiple consecutive newlines in a file.
 */

namespace OZ\Sniffs\Whitespace;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

class MultipleEmptyLinesSniff implements Sniff
{
	/**
	 * Registers the tokens that this sniff wants to listen for.
	 *
	 * @return array
	 */
    public function register(): array
    {
		return [
            T_WHITESPACE
        ];
	}

    /**
     * Called when one of the token types that this sniff is listening for
     * is found.
     *
     * The stackPtr variable indicates where in the stack the token was found.
     * A sniff can acquire information this token, along with all the other
     * tokens within the stack by first acquiring the token stack:
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The PHP_CodeSniffer file where the
     *                                               token was found.
     * @param int                         $stackPtr  The position in the PHP_CodeSniffer
     *                                               file's token stack where the token
     *                                               was found.
     */
	public function process( File $phpcsFile, $stackPtr )
    {
		$tokens = $phpcsFile->getTokens();

		if (
            $stackPtr <= 2
            || $tokens[ $stackPtr - 1 ][ 'line' ] >= $tokens[ $stackPtr ][ 'line' ]
            || $tokens[ $stackPtr - 2 ][ 'line' ] !== $tokens[ $stackPtr - 1 ][ 'line' ]
        ) {
			return;
		}

		$next = $phpcsFile->findNext(
            T_WHITESPACE,
            $stackPtr,
            null,
            true
        );

		if ( $tokens[ $next ][ 'line' ] - $tokens[ $stackPtr ][ 'line' ] > 1 ) {
            $is_fixed = $phpcsFile->addFixableError(
                'Multiple empty lines should not exist in a row; found %s consecutive empty lines',
                $stackPtr,
                'MultipleEmptyLines',
                [ $tokens[ $next ][ 'line' ] - $tokens[ $stackPtr ][ 'line' ] ]
            );

            if ( $is_fixed === true ) {
                $phpcsFile->fixer->beginChangeset();
                $index = $stackPtr;

                while ( $tokens[ $index ][ 'line' ] !== $tokens[ $next ][ 'line' ] ) {
                    $phpcsFile->fixer->replaceToken( $index, '' );
                    $index++;
                }

                $phpcsFile->fixer->addNewlineBefore( $index );
                $phpcsFile->fixer->endChangeset();
            }
		}
	}
}
