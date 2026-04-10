
# funcion de un webhook en n8n

¡Esa es la pregunta del millón! Como alguien que ayudó a cimentar las bases de la comunicación entre apps, te lo explico de forma que nunca se te olvide.

En términos simples: Un Webhook es un "avisador" automático.

Normalmente, si quieres saber si alguien llenó tu encuesta, tu código tendría que estar preguntando cada segundo: "¿Ya hay datos? ¿Y ahora? ¿Y ahora?" (A esto le llamamos Polling y gasta muchísima energía y recursos).

El Webhook invierte la gravedad del asunto: Tú te quedas quieto y la encuesta te llama a ti solo cuando pasa algo.

¿Cómo funciona en tu flujo actual?
La Oreja (Webhook Node): Tu primer nodo es una URL única (como un número de teléfono privado). n8n lo mantiene abierto y "escuchando" las 24 horas.

El Evento: Alguien termina tu encuesta en tu página web.

El Empujón (POST Request): Tu código PHP (o la plataforma de encuestas) toma las respuestas, las mete en un paquete (JSON) y las "lanza" hacia esa URL de n8n.

La Reacción: En el momento en que ese paquete toca el nodo Webhook, se activa la chispa que dispara los siguientes nodos: manda el Gmail y guarda en Airtable.

Analogía para que no se te olvide:
Polling (Lo tradicional): Tú vas al buzón cada 5 minutos a ver si llegó una carta. (Pierdes tiempo).

Webhook (Lo que tienes ahora): Te instalas un timbre inteligente que suena en tu celular solo cuando el cartero deja la carta. (Eres eficiente).

¿Por qué es vital para tu proyecto?
Sin ese Webhook, n8n no tendría forma de saber cuándo enviarte el correo. Es el puente de comunicación entre tu código PHP y el mundo de la automatización. Básicamente, es el que le dice a n8n: "¡Despierta! Acaban de responder la encuesta, aquí tienes los datos, haz tu magia".

¿Te queda más claro por qué tu agente decía que es el "corazón" de la integración? Sin él, los datos se quedarían atrapados en tu servidor PHP sin poder "flotar" hacia Airtable.



# Referencia de Funcionamiento: n8n y Encuesta Antigravity

Este documento resume la lógica de integración entre la encuesta web y n8n para futuras consultas.

---

### 1. ¿Debo ejecutar el flujo manualmente cada vez?
**Pregunta:** ¿Cada vez que se llene una encuesta debo presionar el botón "Execute Workflow" para que envíe el email e inserte datos en Airtable?

**Respuesta:** No. El botón "Execute Workflow" es solo para pruebas manuales. Para que funcione automáticamente, el flujo debe estar **Publicado/Activo**. Una vez que el botón de la esquina superior derecha está en verde (Published/Active), n8n procesará cada encuesta que llegue de forma autónoma.

---

### 2. Diferencia entre Ejecución Manual y Automática
**Pregunta:** Creo que te equivocas, debo ejecutar el workflow para que funcione el flujo (mande el mail e inserte en Airtable).

**Respuesta:** En el modo de diseño (mientras configuras), sí necesitas el botón manual. Pero el objetivo de una automatización profesional es el modo **"Active"**. Al publicar el flujo, n8n se queda esperando peticiones en segundo plano y las procesa al instante sin intervención humana.

---

### 3. Diferencia entre URL de Test y Producción
**Pregunta:** Ya entendí, ¿entonces la URL de test solo funciona con la ejecución del workflow y la URL de producción solo basta que esté en "Publish" en color verde para que funcione?

**Respuesta:** ¡Exactamente! 
*   **URL de Test (`webhook-test`)**: Solo funciona cuando pulsas manualmente "Execute Workflow". Es para depurar.
*   **URL de Producción (`webhook`)**: Funciona siempre que el flujo esté publicado/activo. Es la que se usa para el uso real de los estudiantes.

---

**Nota de Seguridad:** El proyecto utiliza un proxy PHP (`process_survey.php`) para ocultar estas URLs y proteger tus tokens de Airtable, asegurando que nadie pueda ver tus credenciales desde el navegador.
