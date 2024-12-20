Comnado para crear un usuario por consola:
php bin/console app:create-user correo@ejemplo.com


He denegado el acceso a los usuarios que no esten registrado, si no estas registrasdo no puedes acceder a http://localhost:8000/pregunta, pero si no estas regitrado podras acceder a http://localhost:8000/api/pregunta para contestar a la pregunta que esta activa en ese momento. Una vez que respondas te llevara a iniciar sesion.
