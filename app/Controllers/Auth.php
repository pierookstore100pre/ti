<?php

namespace App\Controllers;

use App\Models\ClienteModel;
use App\Models\UsuarioModel;

/** Registro, login y logout (RF06, RF07, HU02) */
class Auth extends BaseController
{
    public function registro()
    {
        if ($this->usuario()) {
            return redirect()->to('/');
        }
        return $this->vista('auth/registro', ['titulo' => 'Crear cuenta']);
    }

    public function guardarRegistro()
    {
        $reglas = [
            'nombres'          => 'required|min_length[2]|max_length[100]',
            'apellidos'        => 'required|min_length[2]|max_length[100]',
            'dni_ruc'          => 'required|regex_match[/^(\d{8}|\d{11})$/]',
            'telefono'         => 'permit_empty|max_length[20]',
            'email'            => 'required|valid_email|max_length[150]|is_unique[usuarios.email]',
            'password'         => 'required|min_length[8]|max_length[72]',
            'password_confirm' => 'required|matches[password]',
        ];
        $mensajes = [
            'dni_ruc'  => ['regex_match' => 'El DNI debe tener 8 dígitos (o RUC de 11).'],
            'email'    => ['is_unique' => 'Ese correo ya está registrado.'],
            'password' => ['min_length' => 'La contraseña debe tener al menos 8 caracteres.'],
            'password_confirm' => ['matches' => 'Las contraseñas no coinciden.'],
        ];

        if (! $this->validate($reglas, $mensajes)) {
            return redirect()->back()->withInput()->with('errores', $this->validator->getErrors());
        }

        $post = $this->request->getPost();

        $usuarios  = new UsuarioModel();
        $usuarioId = $usuarios->insert([
            'nombre'   => trim($post['nombres'] . ' ' . $post['apellidos']),
            'email'    => $post['email'],
            'password' => password_hash($post['password'], PASSWORD_DEFAULT),
            'rol'      => 'Cliente',   // HU02: rol Cliente automático
            'activo'   => 1,
        ], true);

        (new ClienteModel())->insert([
            'usuario_id' => $usuarioId,
            'nombres'    => $post['nombres'],
            'apellidos'  => $post['apellidos'],
            'dni_ruc'    => $post['dni_ruc'],
            'telefono'   => $post['telefono'] ?: null,
            'email'      => $post['email'],
        ]);

        return redirect()->to('login')->with('exito', 'Cuenta creada. Ya puedes iniciar sesión.');
    }

    public function login()
    {
        if ($this->usuario()) {
            return redirect()->to('/');
        }
        return $this->vista('auth/login', ['titulo' => 'Iniciar sesión']);
    }

    public function autenticar()
    {
        $email    = trim((string) $this->request->getPost('email'));
        $password = (string) $this->request->getPost('password');

        $user = (new UsuarioModel())->porEmail($email);

        if (! $user || ! password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Correo o contraseña incorrectos.');
        }

        $this->session->regenerate(true); // evita fijación de sesión
        $this->session->set('usuario', [
            'id'     => (int) $user['id'],
            'nombre' => $user['nombre'],
            'email'  => $user['email'],
            'rol'    => $user['rol'],
        ]);

        return in_array($user['rol'], self::ROLES_STAFF, true)
            ? redirect()->to('admin')
            : redirect()->to('/');
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/')->with('exito', 'Sesión cerrada.');
    }
}
