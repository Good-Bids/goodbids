<?php
/**
 * Summary of class-pem-converter
 *
 * @package PEM
 */

if ( ! class_exists( 'Pem_Converter' ) ) {
	/**
	 * Summary of Pem_Converter
	 *
	 * Creates the PEM files for public key for RSA algorithm support.
	 */
	class Pem_Converter {
		/**
		 * Summary of pem
		 *
		 * @var mixed
		 */
		private $pem;

		/**
		 * Summary of values
		 *
		 * @var mixed
		 */
		private $values;

		/**
		 * Summary of __construct
		 *
		 * Contructor function.
		 *
		 * @param mixed $pem to construct object.
		 */
		public function __construct( $pem ) {
			$this->pem = $this->sanitize_pem( $pem );
		}

		/**
		 * Summary of unpack_pem
		 *
		 * @return void
		 */
		public function unpack_pem() {
			$res = openssl_pkey_get_private( $this->pem );
			if ( false === $res ) {
				$res = openssl_pkey_get_public( $this->pem );
			}

			if ( false === $res ) {
				wp_send_json(
					array(
						'error'         => 'invalid_client_key',
						'error_message' => 'Invalid Client Key',
					),
					401
				);
			}
			$details             = openssl_pkey_get_details( $res );
			$this->values['kty'] = 'RSA';
			$keys                = array(
				'n'  => 'n',
				'e'  => 'e',
				'd'  => 'd',
				'p'  => 'p',
				'q'  => 'q',
				'dp' => 'dmp1',
				'dq' => 'dmq1',
				'qi' => 'iqmp',
			);
			foreach ( $details['rsa'] as $key => $value ) {
				if ( in_array( $key, $keys, true ) ) {
					$value = $this->base64url_encode( $value );
					$this->values[ array_search( $key, $keys, true ) ] = $value;
				}
			}
			$this->values['use'] = 'sig';
		}

		/**
		 * Summary of sanitize_pem
		 *
		 * Sanitizes the PEM.
		 *
		 * @param mixed $pem to sanitize.
		 * @return string
		 */
		public function sanitize_pem( $pem ) {
			preg_match_all( '#(-.*-)#', $pem, $matches, PREG_PATTERN_ORDER );
			$ciphertext = preg_replace( '#-.*-|\r|\n| #', '', $pem );
			$pem        = $matches[0][0] . PHP_EOL;
			$pem       .= chunk_split( $ciphertext, 64, PHP_EOL );
			$pem       .= $matches[0][1] . PHP_EOL;
			return $pem;
		}

		/**
		 * Summary of base64url_encode
		 *
		 * Encodes the data to bse 64.
		 *
		 * @param mixed $data the data to encode.
		 * @return string
		 */
		private function base64url_encode( $data ) {
			return rtrim( strtr( base64_encode( $data ), '+/', '-_' ), '=' ); //phpcs:ignore
		}

		/**
		 * Summary of base64url_decode
		 *
		 * Decodes the base 64 data.
		 *
		 * @param mixed $data the data to decode.
		 * @return bool|string
		 */
		private function base64url_decode( $data ) {
			return base64_decode( str_pad( strtr( $data, '-_', '+/' ), strlen( $data ) % 4, '=', STR_PAD_RIGHT ) ); //phpcs:ignore
		}

		/**
		 * Summary of get_values
		 *
		 * @return array<string>
		 */
		public function get_values() {
			return $this->values;
		}
	}

}
