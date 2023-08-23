<h1 class="nombre-pagina">Olvide Password</h1>
<p class="descripcion-pagina">Reestablece tu password</p>

<?php 
    include_once __DIR__ . "/../templates/alertas.php"
?> 

<form class="formulario" method="POST" action="/olvide">
    <div class="campo">
        <label for="email">Email</label>
        <input
            type="email"
            id="email"
            placeholder="Tu Email"
            name="email"
        />
    </div>

    <input type="submit" class="boton" value="Enviar Intrucciones">
</form>    

<div class="acciones">        
    <a href="/">¿Ya tienes una cuenta? Inicia Sesion?</a>
    <a href="/crear-cuenta">¿Aún no tienes cuenta? Crea Una</a>
</div>