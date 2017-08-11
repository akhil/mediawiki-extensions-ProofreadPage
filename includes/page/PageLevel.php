<?php

namespace ProofreadPage\Page;

use IP;
use User;

/**
 * @licence GNU GPL v2+
 *
 * Proofreading level of a Page: page
 */
class PageLevel {

	/**
	 * @var integer proofreading level of the page
	 */
	protected $level = 1;

	/**
	 * @var User|null last user of the page
	 */
	protected $user = null;

	/**
	 * Constructor
	 * @param int $level
	 * @param User|null $user
	 */
	public function __construct( $level = 1, User $user = null ) {
		$this->level = $level;
		$this->user = $user;
	}

	/**
	 * returns the proofreading level
	 * @return int
	 */
	public function getLevel() {
		return $this->level;
	}

	/**
	 * returns the user
	 * @return User|null
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * Returns if the level is valid
	 *
	 * @return bool
	 */
	public function isValid() {
		return is_integer( $this->level ) && 0 <= $this->level && $this->level <= 4;
	}

	/**
	 * Returns if the level is the same as the level $that
	 *
	 * @param PageLevel $that
	 * @return bool
	 */
	public function equals( PageLevel $that = null ) {
		if ( $that === null ) {
			return false;
		}

		return
			$this->level === $that->getLevel() &&
			( $this->user === null && $that->getUser() === null ||
				$this->user instanceof User && $that->getUser() instanceof User &&
				$this->user->getName() === $that->getUser()->getName() );
	}

	/**
	 * Returns if the change of level to level $to is allowed
	 *
	 * @param PageLevel $to
	 * @return bool
	 */
	public function isChangeAllowed( PageLevel $to ) {
		if ( $this->level !== $to->getLevel() && ( $to->getUser() === null ||
			!$to->getUser()->isAllowed( 'pagequality' ) )
		) {
			return false;
		}

		$fromUser = ( $this->user instanceof User ) ? $this->user : $to->getUser();
		if ( $to->getLevel() === 4 && ( $this->level < 3 || $this->level === 3 &&
			$fromUser->getName() === $to->getUser()->getName() ) &&
			!$to->getUser()->isAllowed( 'pagequality-admin' )
		) {
			return false;
		}

		return true;
	}

	/**
	 * Parse an user name
	 *
	 * @param string $name
	 * @return User|null
	 */
	public static function getUserFromUserName( $name = '' ) {
		if ( $name === '' ) {
			return null;
		} elseif ( IP::isValid( $name ) ) {
			return User::newFromName( IP::sanitizeIP( $name ), false );
		} else {
			$user = User::newFromName( $name );
			return ( $user === false ) ? null : $user;
		}
	}

	/**
	 * @return string
	 */
	public function getLevelCategoryName() {
		return wfMessage( 'proofreadpage_quality' . $this->level . '_category' )
			->inContentLanguage()->plain();
	}
}
