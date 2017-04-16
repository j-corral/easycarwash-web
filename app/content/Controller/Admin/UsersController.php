<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 09/09/15
 * Time: 21:51
 */

namespace Content\Controller\Admin;


use Content\Table\UserTable;
use Core\App;
use Core\Database\QueryBuilder;

/**
 * @property UserTable $User
 */
class UsersController extends AppController {

	public function index( $page = 1 ) {

		if ( ! App::getAuth()->hasAccess( 'users.index' ) ) {
			throw new \Exception( 'Access denied !' );
		}

		$nbPerPage = ( isset( $this->request->get->perPage ) ? $this->request->get->perPage : 25 );

		// orderby sur les <> collones
		$orderby = ( isset( $this->request->get->orderby ) ? $this->request->get->orderby : "0" );


		// Pagination
		$paginate = $this->paginate( 'admin.users.index', $nbPerPage, $page, $orderby );


		$users   = $paginate['req'];
		$nbPages = $paginate['nbPages'];
		$page    = $paginate['page'];
		$total   = $paginate['total'];
		// vue
		$this->render( 'admin.users.index', compact( 'users', 'nbPages', 'page', 'limitelem', 'total' ) );

	}

	public function read( $id ) {
		if ( ! App::getAuth()->hasAccess( 'users.read' ) ) {
			throw new \Exception( 'Access denied !' );
		}

		$user = $this->User->findById( $id );


		$this->render( 'admin.users.read', compact( 'user' ) );
	}


	public function delete( $id ) {
		if ( ! App::getAuth()->hasAccess( 'users.delete' ) ) {
			throw new \Exception( 'Access denied !' );
		}
		var_dump( $id );
		if ( ! $this->User->deleteRow( $id ) ) {
			die( 'Unable to delete ' . $id );
		}

		App::getInstance()->redirect( 'admin.users.index' );
	}

	public function deleteselection() {

		if ( ! App::getAuth()->hasAccess( 'users.delete' ) ) {
			throw new \Exception( 'Access denied !' );
		}
		// tableau des id selectionnés
		$data = (array) $this->request->post->delete;

		if ( ! $this->User->deleteRowSelection( $data ) ) {
			die( 'Unable to delete ' );
		}

		App::getInstance()->redirect( 'admin.users.index' );
	}

	public function save() {

		$form = $this->request->post->User;

		if ( ! $this->check_csrf( $form->token ) ) {
			App::getInstance()->redirect( 'admin.users.index' );
		}

		$pseudo     = filter_var( $form->pseudo, FILTER_SANITIZE_STRING );
		$email      = filter_var( $form->email, FILTER_SANITIZE_EMAIL );
		$salt       = sha1( uniqid( true ) );

		$firstname  = filter_var( $form->firstname, FILTER_SANITIZE_STRING );
		$lastname   = filter_var( $form->lastname, FILTER_SANITIZE_STRING );
		$gender     = filter_var( $form->gender, FILTER_SANITIZE_STRING );
		$birth_date = filter_var( $form->birth_date, FILTER_SANITIZE_STRING );
		$auth_id    = filter_var( $form->auth_id, FILTER_SANITIZE_NUMBER_INT );

		if ( ! isset( $form->id ) ) {

			$password   = hash( 'sha512', $form->password . $salt );

			$this->User->insertRow( compact( 'pseudo', 'email', 'password', 'firstname', 'lastname', 'gender', 'birth_date', 'salt', 'auth_id' ) );
		} else {

			$this->User->updateRow( compact( 'pseudo', 'email', 'firstname', 'lastname', 'gender', 'birth_date', 'auth_id' ), $form->id );
		}

		App::getInstance()->redirect( 'admin.users.index' );


		//$this->render( 'admin.users.save' );
	}

	public function getFormData( $data ) {
		$formData = [ ];

		$pseudo     = "";
		$email      = "";
		$password   = "";
		$firstname  = "";
		$lastname   = "";
		$gender     = "";
		$birth_date = "";
		$auth_id    = "";

		if ( ! empty( $data ) ) {
			$id           = $data->id;
			$pseudo     = $data->pseudo;
			$email      = $data->email;
			$password   = $data->password;
			$firstname  = $data->firstname;
			$lastname   = $data->lastname;
			$gender     = intval( $data->gender );
			$birth_date = $data->birth_date;
			$auth_id    = intval( $data->auth_id );


			$formData[] = [
				'type' => 'input',
				'name' => 'id',
				'args' => [
					'type'  => 'text',
					'value' => $id,
					'label' => 'Id',
					'attr'  => [
						'readonly' => ''
					]
				]
			];


		}



		$formData[] = [
			'type' => 'input',
			'name' => 'pseudo',
			'args' => [
				'type'  => 'text',
				'value' => $pseudo,
				'label' => 'Nickname'
			]
		];
		$formData[] = [
			'type' => 'input',
			'name' => 'email',
			'args' => [
				'type'  => 'text',
				'value' => $email,
				'label' => 'Email'
			]
		];


		if ( empty( $data ) ) {
			$formData[] = [
				'type' => 'input',
				'name' => 'password',
				'args' => [
					'type'  => 'password',
					'value' => $password,
					'label' => 'Password'
				]
			];
		}

		$formData[] = [
			'type' => 'input',
			'name' => 'firstname',
			'args' => [
				'type'  => 'text',
				'value' => $firstname,
				'label' => 'First name'
			]
		];

		$formData[] = [
			'type' => 'input',
			'name' => 'lastname',
			'args' => [
				'type'  => 'text',
				'value' => $lastname,
				'label' => 'Last Name'
			]
		];

		$formData[] = [
			'type' => 'input',
			'name' => 'gender',
			'args' => [
				'type'  => 'number',
				'value' => $gender,
				'label' => 'Gender (M/F)'
			]
		];

		$formData[] = [
			'type' => 'input',
			'name' => 'birth_date',
			'args' => [
				'type'  => 'text',
				'value' => $birth_date,
				'label' => 'Birth date',
				//'class' => 'datepicker'
			]
		];

		$formData[] = [
			'type' => 'input',
			'name' => 'auth_id',
			'args' => [
				'type'  => 'number',
				'value' => $auth_id,
				'label' => 'auth id'
			]
		];

		$formData[] = [
			'type' => 'submit',
			'name' => 'Save',
			'args' => [
			]
		];

		return $formData;
	}

	public function getForm() {
		return [
			'name' => 'User',
			'data' => [
				"title"  => "Create new user",
				"method" => "post",
				"action" => "admin.users.save"
			]
		];
	}


	public function getFormEdit() {
		return [
			'name' => 'User',
			'data' => [
				"title"  => "Edit user",
				"method" => "post",
				"action" => "admin.users.save"
			]
		];
	}


	public function update( $id ) {

		if ( ! App::getAuth()->hasAccess( 'users.update' ) ) {
			throw new \Exception( 'Access denied !' );
		}

		$user = $this->User->findById( $id );

		$formData = $this->getFormData( $user );
		$form     = $this->getFormEdit( $id );


		$this->render( 'admin.crud.edit', compact( 'formData', 'form', 'user' ) );
	}

}