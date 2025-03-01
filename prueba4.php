<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    header("Content-Type: application/json");

    // Array de API Keys (mantenlas privadas, no las expongas en el frontend)
    $apiKeys = [
        "AIzaSyCipn5CO3HqdOOHvwzIqLCxphYmUsuNoek",
        "AIzaSyB0zVWfh990cEIN8DGQUxshy8upVdgtPNI",
        "AIzaSyB4T5S4jhx1j2kO5e6XJahCLKuEA_H2oiY",
        "AIzaSyDLPFn-6r8ZVT9gnO6xEKF82TWtcgOLWzE"
    ];

    function validarApiKey($apiKey) {
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-pro-latest:generateContent?key=$apiKey";
        $data = json_encode(["contents" => [["parts" => [["text" => "Hola"]]]]]);
        
        $options = [
            "http" => [
                "header"  => "Content-Type: application/json",
                "method"  => "POST",
                "content" => $data
            ]
        ];

        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        return $result !== false;
    }

    function obtenerApiKeyValida($keys) {
        foreach ($keys as $key) {
            if (validarApiKey($key)) return $key;
        }
        return null;
    }

    $userInput = trim($_POST["question"] ?? "");

    if (empty($userInput)) {
        echo json_encode(["error" => "La pregunta no puede estar vacía."]);
        exit;
    }

    $selectedApiKey = obtenerApiKeyValida($apiKeys);

    if (!$selectedApiKey) {
        echo json_encode(["error" => "No hay API Key válida disponible."]);
        exit;
    }

    // Generar respuesta con la API
    $prompt = "Responde como un experto en normativas académicas sobre el Reglamento Estudiantil: $userInput";
    $requestData = json_encode(["contents" => [["parts" => [["text" => $prompt]]]]]);

    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-pro-latest:generateContent?key=$selectedApiKey";

    $options = [
        "http" => [
            "header"  => "Content-Type: application/json",
            "method"  => "POST",
            "content" => $requestData
        ]
    ];

    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === false) {
        echo json_encode(["error" => "Error en la generación de contenido."]);
    } else {
        $responseData = json_decode($result, true);
        $textResponse = $responseData["candidates"][0]["content"]["parts"][0]["text"] ?? "No se pudo obtener respuesta.";
        echo json_encode(["answer" => $textResponse]);
    }

    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generador de texto sobre Reglamento Estudiantil</title>
    <style>
        body {
            font-family: 'Roboto', 'Segoe UI', Arial, sans-serif;
            background-color: #f8f9fa;
            color: #202124;
            margin: 0;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }

        h1 {
            color: #4285F4;
            text-align: center;
        }

        .container {
            background-color: #ffffff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        label, textarea, button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
        }

        button {
            background-color: #4285F4;
            color: white;
            border: none;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #3367d6;
        }

        .response-container {
            margin-top: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border-left: 4px solid #34A853;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Generador de texto sobre el Reglamento Estudiantil</h1>
        <div class="input-group">
            <label for="inputText">Compa, escríbeme tu pregunta sobre el reglamento estudiantil:</label>
            <textarea id="inputText" placeholder="Ejemplo: ¿Cuáles son los derechos de los estudiantes?..."></textarea>
            <button id="generateButton">Generar respuesta</button>
        </div>
        <div class="response-container">
            <h3>Respuesta:</h3>
            <p id="responseText"></p>
        </div>
    </div>

    <script>
        document.getElementById("generateButton").addEventListener("click", async () => {
            const userInput = document.getElementById("inputText").value.trim();
            const responseText = document.getElementById("responseText");

            if (!userInput) {
                alert("Compa, por favor, escribe algo para generar una respuesta.");
                return;
            }

            responseText.innerText = "Compa, generando respuesta...";

            try {
                const res = await fetch("", {  // Enviar al mismo archivo PHP
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: new URLSearchParams({ question: userInput })
                });

                const data = await res.json();
                if (data.error) throw new Error(data.error);

                responseText.innerText = `Compa, aquí tienes la respuesta: ${data.answer}`;
            } catch (error) {
                responseText.innerText = `Compa, hubo un error: ${error.message}`;
            }
        });
    </script>
</body>
</html>
