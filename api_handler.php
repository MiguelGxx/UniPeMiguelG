<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$apiKeys = [
    "Ponerapiaqui1",
    "Ponerapiaqui2",
    "Ponerapiaqui3",
    "Ponerapiaqui4"
];

function validarApiKey($apiKey) {
    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-pro-latest:generateContent?key=" . $apiKey;
    $data = json_encode(["contents" => [["parts" => [["text" => "Hola"]]]]]);

    $options = [
        "http" => [
            "header"  => "Content-Type: application/json\r\n",
            "method"  => "POST",
            "content" => $data
        ]
    ];

    $context = stream_context_create($options);
    $response = @file_get_contents($url, false, $context);

    if ($response === false) {
        return false;
    }

    $jsonResponse = json_decode($response, true);
    return isset($jsonResponse["candidates"]);  // Si hay candidatos, la API Key es válida
}

function obtenerApiKeyValida() {
    global $apiKeys;

    foreach ($apiKeys as $key) {
        if (validarApiKey($key)) {
            return $key;
        }
    }
    return null;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = json_decode(file_get_contents("php://input"), true);
    $pregunta = trim($input["pregunta"] ?? "");

    if (!$pregunta) {
        echo json_encode(["error" => "Compa, por favor escribe una pregunta."]);
        exit;
    }

    $apiKey = obtenerApiKeyValida();

    if (!$apiKey) {
        echo json_encode(["error" => "Compa, ninguna API Key es válida."]);
        exit;
    }

    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-pro-latest:generateContent?key=" . $apiKey;
    $finalPrompt = "Actúa como un experto en normativas académicas y responde exclusivamente preguntas sobre el Reglamento Estudiantil. Si la pregunta no está relacionada con el reglamento, indica que solo puedes proporcionar información sobre este tema. Ahora responde a esta consulta usando estos datos Reglamento Estudiantil de Pregrado
Consejo Superior Acuerdo 025 del 03 de agosto de 2007
CAPÍTULO I: DE LA ADMISIÓN A LA UNIVERSIDAD
ARTÍCULO 1º. CALIDAD DE ESTUDIANTE. Es estudiante de pregrado de la Universidad Pedagógica Nacional, en adelante LA UNIVERSIDAD, la persona que habiendo sido oficialmente admitida, previo cumplimiento de todos los requisitos fijados en el respectivo reglamento, tiene matrícula vigente en uno de los programas de formación de pregrado.
ARTÍCULO 2º. ADMISIÓN. La admisión es el acto mediante el cual el Consejo Académico reconoce al aspirante el derecho a cursar un programa académico de formación. La admisión podrá hacerse: a. Por primera vez b. Por readmisión c. Por transferencia
Por primera vez: En el caso de los aspirantes admitidos para iniciar uno de los programas académicos de pregrado en LA UNIVERSIDAD.
Por readmisión: Cuando se ha perdido la calidad de estudiante por un período de tiempo no mayor a un (1) año y se desea continuar con los estudios. La readmisión se concederá por una sola vez.
Por transferencia: Para quienes desean continuar sus estudios en LA UNIVERSIDAD habiéndolos iniciado en otra institución de educación superior, debidamente reconocida por el Estado o habiéndolos terminado en una normal superior con la cual LA UNIVERSIDAD tenga convenio. Las transferencias sólo se concederán a aquellos estudiantes que no hayan interrumpido sus estudios universitarios por más de un (1) año.
Parágrafo 1. En casos excepcionales y justificados, el Consejo Académico podrá autorizar una segunda readmisión a estudiantes que se encuentren en el ciclo de profundización.
Parágrafo 2. Los egresados de escuelas normales superiores a los que se refiere este artículo, tendrán máximo dos (2) años a partir de su grado, para adelantar el proceso de transferencia a LA UNIVERSIDAD. En casos excepcionales y justificados, el Consejo Académico podrá autorizar la realización del proceso a egresados de escuelas normales con más de dos (2) años de haber obtenido el título de normalista.
Parágrafo 3. El Consejo Académico reglamentará los procesos y procedimientos para la admisión a LA UNIVERSIDAD.
CAPÍTULO II: DEL PROCESO DE MATRÍCULA Y PERMANENCIA
ARTÍCULO 3º. MATRÍCULA. La matrícula es el acto voluntario mediante el cual el estudiante se compromete con su firma, a adelantar un programa académico de formación, a ejercer todos sus derechos y cumplir todos los deberes inherentes a la calidad de estudiante, de acuerdo con la normatividad de LA UNIVERSIDAD; por su parte, ésta lo incorpora y se compromete a proporcionarle las condiciones necesarias para su formación profesional.
Parágrafo 1. La matrícula deberá renovarse periódicamente dentro de los plazos señalados por LA UNIVERSIDAD y con el cumplimiento de los procedimientos establecidos.
Parágrafo 2. La firma del estudiante no será requerida cuando LA UNIVERSIDAD disponga de los medios electrónicos necesarios para suplir la asistencia del estudiante a LA UNIVERSIDAD.
ARTÍCULO 4º. REGISTRO ACADÉMICO. El estudiante deberá realizar el registro de sus espacios académicos dentro del plazo establecido en el calendario académico.
ARTÍCULO 5º. CANCELACIÓN Y MODIFICACIÓN DE MATRÍCULA. El estudiante podrá cancelar total o parcialmente la matrícula dentro de los plazos fijados por LA UNIVERSIDAD, bajo las siguientes condiciones: a. La cancelación parcial podrá efectuarse en las primeras seis (6) semanas del semestre. b. La cancelación total del semestre implicará el retiro temporal del estudiante y deberá solicitarse dentro de las fechas estipuladas.
CAPÍTULO III: DE LA EVALUACIÓN Y PROMOCIÓN
ARTÍCULO 6º. SISTEMA DE EVALUACIÓN. La evaluación del aprendizaje es un proceso permanente que tiene como finalidad valorar el rendimiento académico del estudiante en cada espacio académico.
ARTÍCULO 7º. ESCALA DE CALIFICACIÓN. LA UNIVERSIDAD empleará una escala de calificación de 0 a 50 puntos, aprobándose un espacio académico con mínimo 30 puntos.
ARTÍCULO 8º. PROMEDIOS PONDERADOS. Se calcularán promedios ponderados semestrales y acumulados para determinar el desempeño académico del estudiante.
ARTÍCULO 9º. PRUEBAS SUPLETORIAS Y VALIDACIONES. Los estudiantes podrán presentar pruebas supletorias en caso de fuerza mayor debidamente justificada. Asimismo, podrán validar conocimientos en espacios académicos mediante exámenes de suficiencia.
ARTÍCULO 10º. REPITENCIA Y PÉRDIDA DEL ESTATUS DE ESTUDIANTE. Un estudiante que repruebe un curso en tres (3) ocasiones perderá su calidad de estudiante.
CAPÍTULO IV: DEL GRADO
ARTÍCULO 11º. REQUISITOS DE GRADO. Para optar por el título de pregrado, el estudiante deberá: a. Aprobar todos los espacios académicos del plan de estudios. b. Sustentar y aprobar un trabajo de grado o cumplir con la alternativa de grado establecida por la facultad. c. Estar a paz y salvo con LA UNIVERSIDAD.
ARTÍCULO 12º. ENTREGA DEL TÍTULO. El título será entregado en ceremonia oficial de grado o por ventanilla según lo estipulado por LA UNIVERSIDAD.
CAPÍTULO V: INCENTIVOS Y DISTINCIONES
ARTÍCULO 13º. MATRÍCULA DE HONOR. Se otorgará matrícula de honor a los estudiantes con promedios sobresalientes y sin sanciones disciplinarias.
ARTÍCULO 14º. MONITORÍAS Y BECAS. LA UNIVERSIDAD podrá otorgar becas y monitorías a estudiantes destacados en el ámbito académico, investigativo y deportivo.
CAPÍTULO VI: DERECHOS Y DEBERES DE LOS ESTUDIANTES
ARTÍCULO 15º. DERECHOS. Los estudiantes tienen derecho a: a. Recibir educación de calidad. b. Hacer uso de las instalaciones y servicios de LA UNIVERSIDAD. c. Participar en órganos colegiados de la comunidad universitaria.
ARTÍCULO 16º. DEBERES. Los estudiantes deberán: a. Cumplir con el presente reglamento y las disposiciones institucionales. b. Mantener el respeto y la convivencia dentro del campus. c. Preservar los bienes universitarios.
CAPÍTULO VII: RÉGIMEN DISCIPLINARIO
ARTÍCULO 17º. FALTAS DISCIPLINARIAS. Se consideran faltas disciplinarias: a. Plagio y fraude académico. b. Actos de violencia dentro del campus. c. Daños a la infraestructura universitaria.
ARTÍCULO 18º. SANCIONES. Dependiendo de la gravedad de la falta, las sanciones podrán ser: a. Amonestaciones escritas. b. Suspensión temporal de la matrícula. c. Expulsión definitiva.
CAPÍTULO VIII: DISPOSICIONES FINALES
ARTÍCULO 19º. REVISIÓN Y MODIFICACIÓN DEL REGLAMENTO. El presente reglamento podrá ser modificado por el Consejo Superior cuando las condiciones lo requieran.
6Reglamento Estudiantil de P r egrado
Que conforme al carácter pedagógico de la Uni-
versidad estipulado en su misión y visión, se reitera el
propósito de formar integralmente educadores com-
prometidos con el desarrollo social y cultural del país.
Que con miras al logro de estos compromisos y fines,
es necesario fijar las normas y principios que regulen la
actividad estudiantil en sus relaciones con la academia
y su entorno social y cultural.
Que se hace necesario actualizar las normas
para agilizar los trámites académicos y hacerlos más
eficientes.
Que el funcionamiento armónico de la comunidad
educativa requiere la existencia de normas que expre-
sen sus valores, su sentido y misión en la sociedad, y
que fortalezcan las dinámicas institucionales para las
mejores relaciones entre los individuos y de éstos con el
ambiente académico y las instancias de la organización.
Por lo expuesto,
ACUERDA:
CAPÍTULO I
DE LA ADMISIÓN A LA UNIVERSIDAD
ARTÍCULO 1º.
CALIDAD DE ESTUDIANTE. Es estudiante de pre-
grado de la Universidad Pedagógica Nacional, que en
adelante se llamará LA UNIVERSIDAD, la persona que
habiendo sido oficialmente admitida, previo cumpli-
miento de todos los requisitos fijados en el respectivo
reglamento, tiene matrícula vigente en uno de los pro-
gramas de formación de pregrado.
ARTÍCULO 2º.
ADMISIÓN. La admisión es el acto mediante el cual el
Consejo Académico reconoce al aspirante el derecho a
cursar un programa académico de formación. La admi-
sión podrá hacerse:
a. Por primera vez
b. Por readmisión
c. Por transferencia
Por primera vez. En el caso de los aspirantes
admitidos para iniciar uno de los programas académicos
de pregrado en LA UNIVERSIDAD.
Por readmisión. Cuando se ha perdido la calidad
de estudiante por un período de tiempo no mayor a
un (1) año y se desea continuar con los estudios. La
readmisión se concederá por una sola vez.
Por transferencia. Para quienes desean con-
tinuar sus estudios en LA UNIVERSIDAD habiéndolos
iniciado en otra institución de educación superior,
debidamente reconocida por el Estado o habiéndo-
los terminado en una normal superior con la cual LA
UNIVERSIDAD tenga convenio. Las transferencias sólo
se concederán a aquellos estudiantes que no hayan
interrumpido sus estudios universitarios por más de
un (1) año.
Parágrafo 1. En casos excepcionales y justificados,
el Consejo Académico podrá autorizar una segunda
readmisión a estudiantes que se encuentren en el ciclo
de profundización.
Parágrafo 2. Los egresados de escuelas normales
superiores a los que se refiere este artículo, tendrán
máximo dos (2) años a partir de su grado, para adelantar
el proceso de transferencia a LA UNIVERSIDAD. En ca-
sos excepcionales y justificados, el Consejo Académico
podrá autorizar la realización del proceso a egresados
de escuelas normales con más de dos (2) años de haber
obtenido el título de normalista.
Parágrafo 3. El Consejo Académico reglamentará
los procesos y procedimientos para la admisión a LA
UNIVERSIDAD.
DEL PROCESO DE MATRÍCULA Y LA PER-
MANENCIA EN LA UNIVERSIDAD
ARTÍCULO 3º. MATRÍCULA. La matrícula es
el acto voluntario mediante el cual el estudiante se
compromete con su firma, a adelantar un programa
académico de formación, a ejercer todos sus derechos
y cumplir todos los deberes inherentes a la calidad
de estudiante, de acuerdo con la normatividad de LA
UNIVERSIDAD; por su parte, ésta lo incorpora y se com-
promete a proporcionarle las condiciones necesarias
para su formación profesional.
Parágrafo 1. La matrícula deberá renovarse
periódicamente dentro de los plazos señalados por
LA UNIVERSIDAD y con el cumplimiento de los proce-
dimientos establecidos.
Parágrafo 2. La firma del estudiante no será
requerida cuando LA UNIVERSIDAD disponga de los
medios electrónicos necesarios para suplir la asistencia
del estudiante a LA UNIVERSIDAD.
ARTÍCULO 4º. PROCESO DE MATRÍCULA.
Para que un estudiante cuente con matrícula vigente
en LA UNIVERSIDAD deberá satisfacer las siguientes
condiciones:
a. Efectuar el registro académico en las fechas
correspondientes.
b. Realizar el pago de derechos de matrícula en las
formas autorizadas por LA UNIVERSIDAD para
el correspondiente periodo.
ARTÍCULO 5º. REGISTRO ACADÉMICO. Es
el acto mediante el cual, en cada período académico,
el estudiante inscribe los espacios académicos que se
compromete a cursar de conformidad con los proce-
dimientos establecidos. El registro requiere el análisis
previo del plan de estudios para dar cumplimiento a
la unidad de los núcleos y ambientes de formación de
cada programa.
Parágrafo 1. El estudiante que cumple la mayoría
de edad debe reportar el nuevo número de documento
de identidad, como requisito para trámite de registro.
Parágrafo 2. Los espacios académicos perdidos
deben ser registrados preferiblemente en el siguiente
período académico; en todo caso, todo espacio acadé-
mico cursado y perdido, aunque no haga parte del plan
de estudios, se tomará en cuenta para el promedio
ponderado acumulado y hará parte de la contabilidad
académica.
Parágrafo 3. El registro de espacios académicos
para un período debe hacerse personalmente en las
fechas establecidas en el calendario académico. En
casos excepcionales, se podrá delegar por escrito a otra
persona para que haga el registro, previa autorización
del coordinador del programa respectivo.
ARTÍCULO 6°. El registro extemporáneo de
espacios académicos será excepcional, y atenderá al
calendario previsto por el Consejo Académico; dicho
registro será autorizado por el decano correspondien-
te, quien ordenará el diligenciamiento del mismo en el
sistema de registro.
ARTÍCULO 7º. MODIFICACIONES DEL
REGISTRO. Las modificaciones del registro o ajustes
al registro son procesos que hacen parte del registro
de espacios académicos en un periodo académico y se
pueden realizar:
a. Por cancelación de un curso por cupo mínimo.
b. Adición de espacios académicos en cursos con
cupo disponible.
c. Cancelación de registro de espacios académicos.
Parágrafo. Las modificaciones de que trata este
artículo, sólo procederán en las fechas previstas en el
calendario operativo de registro.
ARTÍCULO 8º. CANCELACIÓN PARCIAL. Se
entiende por cancelación parcial de registro, la anulación
autorizada de uno o más espacios académicos que el
estudiante tramita ante el departamento al cual está
adscrito. Dicha cancelación podrá realizarse dentro de
las primeras seis (6) semanas de iniciado del periodo
académico, con el cumplimiento de los siguientes
requisitos:
a. No afectar el mínimo de créditos a cursar.
b. Que el espacio académico no se haya cancelado
anteriormente.
c. Cancelar los derechos pecuniarios correspon-
dientes.
Parágrafo 1. Las cancelaciones parciales de
espacios académicos electivos de todo programa, se
tramitarán acogiendo lo reglamentado en el Acuerdo
017 de 2005 del Consejo Superior, o en aquellos que
lo modifiquen o sustituyan.
Parágrafo 2. Pasada la fecha de cancelaciones
ordinarias y hasta la octava (8) semana de iniciado el
periodo académico, el decano de la facultad a la cual
está adscrito el estudiante, podrá autorizar cancela-
ciones parciales de manera excepcional.
ARTÍCULO 9º. RESERVA DE CUPO. El consejo
de facultad puede autorizar la reserva de cupo de un
aspirante admitido, que por circunstancias debidamente
certificadas no pueda iniciar sus estudios. En estos
casos, el admitido debe presentar nuevamente docu-
mentos para liquidación de matrícula en el momento
que se reintegre.
Parágrafo. Igual procedimiento se aplica para
reservas de cupo por servicio militar.
ARTÍCULO 10º. CANCELACIÓN TOTAL DEL
REGISTRO. La cancelación total se realizará mediante
comunicación personal dirigida al director de depar-
tamento al cual está adscrito el estudiante. Una vez
autorizada, el estudiante tendrá un plazo máximo de
dos (2) años para solicitar el reintegro.
La cancelación total del registro podrá solicitarse
hasta tres (3) semanas antes de finalizar las clases
del respectivo período académico. Todo estudiante
debidamente matriculado, podrá tramitar cancelación
total o parcial de registro.
Parágrafo. La cancelación total de registro, será
autorizada por el decano correspondiente, previo es-
tudio del director de departamento.
ARTÍCULO 11°. La decisión de cancelación total
o parcial del registro será autorizada por el decano,
previo estudio del director de departamento e infor-
mada al Jefe de la División de Admisiones y Registro o a
quien haga sus veces, con el objeto de que se oficialice
dicho acto.
ARTÍCULO 12º. REINTEGRO. Es la autoriza-
ción otorgada a una persona que ha hecho reserva
de cupo o cancelación total de registro para continuar
regularmente los estudios en LA UNIVERSIDAD, des-
pués de haber cancelado un período académico, con
el cumplimiento de los requisitos establecidos en el
presente Reglamento.
El consejo de departamento al cual está adscrito
el estudiante, o en su defecto el consejo de facultad,
analizará las solicitudes de reintegro, las cuales serán
autorizadas por el decano correspondiente.
Antes de finalizar los dos (2) años de reserva de
cupo o de haberse producido la cancelación total del
registro, el estudiante deberá solicitar por escrito al
consejo de departamento o a quien haga sus veces, el
reintegro adjuntando la autorización de retiro. Una vez
autorizado el reintegro, podrá renovar su matrícula de
acuerdo con lo establecido en el calendario académico.
Corresponde al director de departamento establecer las
condiciones académicas dentro de las cuales quedará
el estudiante, de acuerdo con los planes de estudio
vigentes.
ARTÍCULO 13°. CAMBIO DE PROGRAMA.
Podrá realizar cambio de programa, por una sola vez,
quien curse estudios en uno de los programas de pre-
grado de LA UNIVERSIDAD y decida trasladarse a otro.
El cambio de programa se autorizará con el cumpli-
miento de los siguientes requisitos:
a. Haber cursado y aprobado los espacios acadé-
micos correspondientes al segundo semestre.
b. Obtener concepto favorable del consejo del
departamento al que pertenece el programa
que cursa y aprobar las pruebas específicas del
programa al que aspira.
Parágrafo. No se concederá el cambio de progra-
ma a ningún estudiante que haya iniciado el ciclo de
profundización.
ARTÍCULO 14º. PÉRDIDA DE LA CALIDAD
DE ESTUDIANTE. La calidad de estudiante se pierde
cuando:
a. Ha completado el programa de estudios en el
que se matriculó.
b. No ha renovado matrícula en las fechas estipu-
ladas en el calendario académico o no ha hecho
uso del derecho al reintegro.
c. No ha cancelado el total de la matrícula dentro
de los plazos acordados.
d. Presenta bajo rendimiento académico.
e. Ha sido sancionado con suspensión por uno
o más semestres o ha sido expulsado de LA
UNIVERSIDAD.
ARTÍCULO 15°. RECUPERACIÓN DE CALI-
DAD DE ESTUDIANTE. Para efectos del presente
Acuerdo, se describen a continuación los requisitos para
recuperar la calidad de estudiante, cuando de acuerdo a
la reglamentación vigente, ésta se ha perdido y no existe
claridad en algunos casos para recuperarla.
a. El estudiante que pierde calidad de tal por
materia perdida por tercera vez, podrá tramitar
nueva admisión, atendiendo el procedimiento
establecido por LA UNIVERSIDAD. En todo
caso el espacio académico perdido debe ser
superado, a través de una prueba de eficiencia.
b. El estudiante que a partir del primer periodo
académico cursado y aprobado abandona
estudios sin realizar cancelación total de re-
gistro, podrá tramitar una nueva admisión. El
departamento respectivo efectuará el estudio
correspondiente y, si es el caso, autorizará la
cancelación extemporánea, para recuperar el
Promedio Ponderado Acumulado, el cual debe
ser igual o superior a treinta (30), para aceptar
el reingreso.
c. Lo mismo se aplicará para quienes pierdan la
calidad de estudiante por Promedio Ponderado
Acumulado inferior a treinta (30).
Parágrafo 1. Los estudios de nueva admisión
se realizarán con base en el plan de estudios vigente,
remitiendo a la División de Admisiones y Registro las
homologaciones respectivas. El número máximo de
solicitudes de nueva admisión que podrá presentar un
estudiante será de tres (3).
Parágrafo 2. El estudiante que reingrese por nue-
va admisión debe presentar nuevamente documentos
para la liquidación de matrícula.
Parágrafo 3. El Consejo Académico actuará como
segunda instancia cuando así se requiera y su decisión
se entenderá como definitiva.
CAPÍTULO III
DE LA EVALUACIÓN Y LA PROMOCIÓN
ARTÍCULO 16°. La evaluación de los estudiantes
es un proceso integral, formativo y permanente, cuya
finalidad es construir espacios de crecimiento humano
y social que promuevan el desarrollo de los contenidos
y valores del conocimiento, de la ética y de la estética,
en el campo profesional específico de los maestros, y
por referencia a un compromiso con la construcción de
la Nación, el fortalecimiento de la democracia y la paz
social. Comprende la evaluación de los aprendizajes,
lo cual permite valorar el desarrollo de competencias,
actitudes, aptitudes, conocimientos, habilidades y
destrezas del estudiante, en un contexto y programa
académico determinado.
Permite además, el diseño e implementación de es-
trategias para cualificar el desarrollo del estudiante, con
miras a alcanzar la excelencia académica. La evaluación
de los aprendizajes incluye la calificación.
ARTÍCULO 17º. La calificación para efectos de
registro y control, se expresará en números enteros
dentro de la escala de cero (0) a cincuenta (50) puntos.
En caso de decimales se aproximará al entero más
próximo por exceso o por defecto.
Parágrafo 1. LA UNIVERSIDAD llevará un registro
sistemático de las calificaciones obtenidas por cada es-
tudiante y expedirá las certificaciones correspondientes.
Al finalizar cada periodo, la División de Admisiones y
Registro expedirá el reporte de notas, en el cual se iden-
tificarán los espacios académicos cursados, el número
de créditos correspondientes, las calificaciones logradas,
el promedio del semestre y el promedio ponderado.
Parágrafo 2. Cuando una prueba sea anulada por
fraude se calificará con cero (0) y el profesor actuará
de conformidad con lo establecido en el capítulo VIII del
presente Reglamento.
ARTÍCULO 18°. Para cada espacio académico
el profesor presentará a los estudiantes el conjunto de
factores que permitan evidenciar, comprender y valorar
el nivel de desarrollo a alcanzar por el estudiante en sus
actividades académicas, así como los diferentes instru-
mentos para la valoración de su nivel de desempeño.
Parágrafo. En todo caso, se programarán al menos
tres (3) momentos o actividades evaluativas durante el
período académico, excepto para la Facultad de Bellas
Artes cuyas modalidades evaluativas serán definidas
por el Consejo de Facultad.
ARTÍCULO 19°. Se considera que un estudiante
presenta bajo rendimiento cuando:
Reprueba un espacio académico por tercera vez.
Obtiene un promedio ponderado acumulado inferior
a treinta (30) puntos.
No ha obtenido el título después de haber cursado
quince (15) períodos académicos.
Reprueba o pierde por inasistencia más de tres
(3) espacios académicos registrados en el período
académico.
ARTÍCULO 20º. PROMEDIO PONDERADO
SEMESTRAL. Es el resultado de dividir la suma de los
productos de las notas de cada espacio académico por
su valor en créditos, entre la suma de los créditos que el
estudiante cursó en el semestre académico respectivo.
ARTÍCULO 21º. PROMEDIO PONDERADO
ACUMULADO. Es el resultado de dividir la suma de los
productos de las notas de todos los espacios académi-
cos cursados durante la carrera por su valor en créditos,
entre la suma del total de créditos cursados.
ARTÍCULO 22º. PRUEBAS. En LA UNIVERSI-
DAD, además de los mecanismos establecidos para la
evaluación académica, se podrán practicar las siguientes
pruebas:
Validación
Supletoria
Prueba de validación. Se aplicará a los estu-
diantes que consideren tener un nivel de conocimientos
suficiente que les permita aprobar un determinado
curso sin necesidad de registrarse en él.
El consejo de facultad, previo concepto del consejo
de departamento o en su defecto del comité académico
de programa, determinará los espacios que sean valida-
bles y los parámetros para la presentación de la prueba.
Ningún estudiante que haya registrado previamente un
espacio académico podrá validarlo.
La validación se aprueba con una calificación igual
o superior a treinta y cinco (35) y sólo en este caso se
registrarán en la hoja de vida. Quien desee presentar
tales pruebas deberán inscribirse previamente en el de-
partamento respectivo. Los consejos de departamento
fijarán los requisitos y fechas para su realización. Esta
prueba se puede presentar una sola vez.
Las pruebas de validación pueden tomarse como
criterio de clasificación para disciplinas y saberes que
requieran determinación de niveles de competencia.
La prueba de validación dejada de presentar sin
justa causa, o que no haya sido cancelada por el estu-
diante con anticipación mínima de dos (2) días hábiles
será calificada con cero (0).
Prueba supletoria. Es aquella que se practica
en reemplazo de una actividad evaluativa parcial o final,
cuando el estudiante no ha podido presentarla por
eventos de fuerza mayor o caso fortuito. La realización
de la prueba la solicita el estudiante por escrito a su
respectivo profesor dentro de los dos (2) días hábiles
siguientes a la fecha de la evaluación.
En el caso de pruebas finales supletorias, los profe-
sores entregarán las notas al departamento respectivo,
antes del registro del nuevo período académico.
Parágrafo. Los estudiantes de últimos semestres
deberán presentar los exámenes de calidad de la educa-
ción superior ECAES, de acuerdo con los procedimientos
previstos por LA UNIVERSIDAD y por la Ley.
ARTÍCULO 23º. APROBACIÓN. La aprobación
de un espacio académico se obtiene con un mínimo de
treinta (30) puntos.
ARTÍCULO 24°. REPROBACIÓN. La repro-
bación de un espacio académico se produce por bajo
rendimiento académico, inasistencia sistemática o
abandono de dicho espacio.
ARTÍCULO 25º. REVISIÓN. Todo estudiante
tiene derecho a solicitar por escrito la reconsideración
de la calificación obtenida en un espacio académico,
dentro de los dos (2) días hábiles siguientes a la publi-
cación de los resultados.
Parágrafo. La revisión de la calificación la hará en
primera instancia el profesor del espacio académico; y,
en segunda instancia, un jurado que designe el director
de departamento, previa solicitud del estudiante.
ARTÍCULO 26º. ASISTENCIA. La asistencia
a los espacios académicos de los programas que re-
quieran presencialidad es obligatoria y el estudiante,
al matricularse, adquiere el compromiso de asistir a la
totalidad de las actividades académicas presenciales.
Parágrafo 1. Cuando sin justa causa, las faltas de
asistencia registradas superen el 20% de las activi-
dades académicas presenciales realizadas y definidas
como obligatorias, el docente encargado del espacio
reportará la calificación de cero (0). Para el caso de la
Facultad de Bellas Artes, la inasistencia en los espacios
académicos del ambiente disciplinar no deberá sobre-
pasar el 15%.
Parágrafo 2. Si un estudiante abandona uno o
varios espacios académicos sin cancelarlos dentro de
los plazos establecidos en el presente reglamento, las
pruebas académicas no presentadas se calificarán con
cero (0) y la calificación definitiva será la resultante de
las pruebas programadas en el transcurso del período.
ARTÍCULO 27°. RECLAMACIONES Y MODI-
FICACIONES DE NOTA. Las reclamaciones de nota
y de registro de espacios académicos sólo procederán
con respecto al periodo inmediatamente anterior, en las
fechas establecidas en los calendarios operativos de
registro definidos por la Vicerrectoría Académica para
cada periodo. Después de estas fechas no procederá
reclamación.
CAPÍTULO IV
DEL GRADO
ARTÍCULO 28º. REQUISITOS. Son requisitos
para optar un título en LA UNIVERSIDAD:
a. Ser estudiante activo de LA UNIVERSIDAD.
b. Haber aprobado la totalidad de los créditos
obligatorios y el porcentaje de créditos electivos
definidos en el plan de estudios.
c. Presentar y sustentar un informe sobre la prác-
tica pedagógica, en el caso que corresponda.
d. Presentar y sustentar un trabajo de grado o
monografía y obtener la respectiva aprobación.
e. Estar a paz y salvo por todo concepto con LA
UNIVERSIDAD.
Parágrafo 1. Los departamentos definirán los
criterios para la elaboración, dirección, presentación
y sustentación del trabajo de grado y del informe de
práctica pedagógica de acuerdo con el plan de estudios.
Estos criterios serán aprobados por los respectivos con-
sejos de facultad y se darán a conocer a los estudiantes
en forma oportuna.
Parágrafo 2. Los estudiantes registrarán el trabajo
de grado como un espacio académico y tendrán un
director asignado por el respectivo programa, durante
máximo dos (2) períodos académicos. El consejo de
facultad podrá autorizar hasta un (1) periodo académico
adicional para los casos que lo ameriten.
Para la asignación del dicho director, será condi-
ción la aceptación del proyecto por parte del comité
respectivo.
ARTÍCULO 29º. EVALUACIÓN. El trabajo de
grado será evaluado por un jurado compuesto por dos
(2) profesores de la comunidad académica, asignados
por el departamento y el director del proyecto.
Parágrafo. LA UNIVERSIDAD otorgará distincio-
nes a los trabajos de grado de acuerdo con la reglamen-
tación vigente para tal fin.
CAPÍTULO V
DE LOS INCENTIVOS Y DISTINCIONES
ARTÍCULO 30º. LA UNIVERSIDAD establecerá
un sistema de incentivos y distinciones a la excelencia
para los estudiantes que se destaquen por su actividad
académica, de investigación, cultural y deportiva, al igual
que por su compromiso y gestión institucional y social.
ARTÍCULO 31º. LA UNIVERSIDAD otorgará los
siguientes incentivos y distinciones:
• Becas
• Monitorias
• Participación en el grupo estudiantil de proto-
colo institucional
• Incentivos a la actividad investigativa
• Incentivos a la actividad cultural, artística y
deportiva
• Representación de LA UNIVERSIDAD
• Divulgación de los mejores trabajos académicos
• Distinción de los trabajos de grado
• Matrículas de honor
• Grado de honor
• Pasantías académicas en otra universidad.
Parágrafo. El otorgamiento de los incentivos y
distinciones será reglamentado, según el caso, por el
Consejo Superior, el Consejo Académico o la Rectoría.
CAPÍTULO VI
DE LOS DERECHOS Y DEBERES
ARTÍCULO 32º. DERECHOS. Son derechos
de los estudiantes, además de los contemplados en la
Constitución Política y en la ley, los siguientes:
a. Recibir tratamiento respetuoso por parte de las
directivas, funcionarios, contratistas, profesores
y compañeros.
b. Cursar el programa de formación previsto y utili-
zar los recursos que LA UNIVERSIDAD le ofrece.
c. Acceder a las fuentes de información que LA
UNIVERSIDAD tiene disponibles para su servicio.
d. Elegir y ser elegidos para las posiciones de re-
presentación que correspondan al estamento
estudiantil, de conformidad con las normas
vigentes.
e. Acceder a los servicios de bienestar que LA
UNIVERSIDAD ofrece, de acuerdo con las posi-
bilidades físicas y financieras y los reglamentos
que se establezcan.
f. Sugerir políticas de bienestar a través de los
representantes estudiantiles en los diferentes
consejos y comités.
g. Presentar por escrito solicitudes y/o reclamos
de orden académico, disciplinario y adminis-
trativo siguiendo siempre el conducto regular.
Así mismo ser asistidos, aconsejados y oídos
por el personal docente y administrativo de LA
UNIVERSIDAD.
h. Conocer oportunamente el resultado de sus
evaluaciones académicas.
i. Ser oídos en descargos e interponer los recur-
sos previstos en el presente Reglamento, en
caso de proceso disciplinario o sanción.
j. Expresar, disentir, discutir y examinar con
libertad y respeto las doctrinas, las ideas, los
conocimientos y la opinión ajena.
k. Participar en la construcción y desarrollo de las
políticas y acciones que les competen a través
del Consejo Estudiantil y demás formas institu-
cionales de representación.
l. Participar en las elecciones reglamentarias que
se realicen en LA UNIVERSIDAD.
m. Promover, participar y representar a LA UNI-
VERSIDAD en las actividades científicas, artís-
ticas, deportivas y culturales que refuercen los
valores establecidos en el Proyecto Educativo
Institucional.
n. Participar a través de sus representantes en la
designación de directivos, según lo determine
el Estatuto General de LA UNIVERSIDAD y las
reglamentaciones específicas.
o. Los demás que se derivan de los estatutos.
ARTÍCULO 33º. DEBERES. Son deberes de
los estudiantes:
• Actuar en provecho de la identidad institucional
de LA UNIVERSIDAD y del fortalecimiento de la
profesionalidad docente.
Atender con responsabilidad sus actividades
académicas.
• Respetar la Constitución Política y las leyes de
la República y cumplir las normas legales, esta-
tutarias y reglamentarias de LA UNIVERSIDAD.
• Asistir y participar en las actividades académicas
presenciales que integran el currículo de su
formación profesional.
• Dar tratamiento respetuoso a las directivas,
funcionarios, contratistas, profesores y com-
pañeros.
• Respetar los derechos y opiniones de los miem-
bros de la comunidad universitaria.
• Preservar los equipos, muebles, materiales y
edificaciones que están a su servicio y respon-
sabilizarse de los daños que ocasionen.
• Representar dignamente a LA UNIVERSIDAD,
responsabilizándose de su comportamiento en
las actividades académicas, culturales, sociales
y deportivas, en que participen en condición de
estudiantes.
• Estar a paz y salvo con todas las unidades
de apoyo académico y administrativo de LA
UNIVERSIDAD.
• Los demás deberes que se deriven de la
Constitución Política, la ley y los estatutos
universitarios.
CAPÍTULO VII
DE LAS ORGANIZACIONES ESTUDIANTILES
ARTÍCULO 34º. Los estudiantes, con arreglo
a las leyes, los estatutos y normas vigentes, podrán
darse órganos de representación estudiantil, con el fin
de generar y fortalecer espacios de participación que
constituyan un aporte a su formación integral como
futuros maestros.
ARTÍCULO 35º. En consonancia con el artículo
anterior, LA UNIVERSIDAD apoyará la conformación
del Consejo Estudiantil que estará integrado por los
representantes estudiantiles de los órganos de direc-
ción, un (1) miembro por cada programa académico de
formación y un (1) representante de cada uno de los
grupos de trabajo académicos, culturales, deportivos,
artísticos o formativos, en un número máximo de cinco
(5) grupos por facultad.
CAPÍTULO VIII
DE LA CONVIVENCIA UNIVERSITARIA
ARTÍCULO 36º. Tal como lo establece el Esta-
tuto Académico, los estudiantes como miembros de la
comunidad universitaria actuarán guiados por principios
de ética, responsabilidad, lealtad y respeto a las per-
sonas, las autoridades, los símbolos, normas y bienes
institucionales, de manera que sus relaciones con LA
UNIVERSIDAD se desarrollen bajo los principios de la
convivencia universitaria democrática, participativa y
plural, la responsabilidad compartida y el reconocimiento
de sí mismo como sujeto responsable de su proceso
de formación.
ARTÍCULO 37º. CONDUCTAS QUE ATEN-
TAN CONTRA EL ORDEN ACADÉMICO, LA LEY,
LOS ESTATUTOS Y REGLAMENTOS UNIVER-
SITARIOS. Son conductas que atentan contra el
orden académico, la ley, los estatutos y reglamentos
universitarios:
• El no cumplir con los reglamentos internos, cir-
culares, manuales y ordenes administrativas de
LA UNIVERSIDAD o de las instituciones nacio-
nales o internacionales a las que el estudiante
asista en representación.
• La sustracción de cuestionarios y documentos.
• El fraude en actividades evaluativas, tal como
suplantación, plagio, copia.
• Acceder de manera fraudulenta al sistema de
información de LA UNIVERSIDAD.
• El irrespeto a las insignias de la patria y de la
Institución.
• El irrespeto, la agresión física, la calumnia e
injuria contra los miembros de la comunidad
universitaria.
• El suministro de información falsa, la falsifi-
cación de documentos y la suplantación de
personas.
• La utilización de las instalaciones de LA UNI-
VERSIDAD para la venta o comercialización de
cualquier producto o servicio no autorizado.
• La retención arbitraria de bienes, el hurto o el
daño en propiedades de LA UNIVERSIDAD o en
propiedades ajenas que se encuentren en los
predios de la misma o a su servicio.
El porte de armas en el recinto universitario o
el porte, la tenencia o guarda de elementos o
materiales explosivos o que sean complemento
o partes útiles de las mismas.
• La guarda, tráfico y/o consumo de sustancias
psicoactivas o alucinógenas en el recinto uni-
versitario.
• El presentarse a LA UNIVERSIDAD o a cualquier
actividad académica, cultural o deportiva en
estado de embriaguez, o bajo el efecto de
cualquier sustancia psicoactiva o alucinógena.
• La retención, intimidación y chantaje a cualquier
miembro de la comunidad universitaria.
• La incitación y participación en bloqueos a las
instalaciones o en actos de sabotaje contra las
actividades académicas, deportivas o culturales
de LA UNIVERSIDAD.
• La realización de tropeles y la provocación de
temor entre los miembros de la comunidad
universitaria.
• Todas las conductas tipificadas como delitos
por las leyes de la República.
ARTÍCULO 38º. VALORACIÓN DE LAS
CONDUCTAS. Para efectos de la sanción, las con-
ductas definidas en el artículo anterior se valorarán
según su naturaleza, sus efectos, las modalidades y
circunstancias del hecho, los motivos determinantes y
los antecedentes personales.
• Para esa valoración se tendrán en cuenta los
siguientes criterios:
• La naturaleza de la falta se apreciará por el daño
físico, intelectual o moral producido.
Las modalidades y circunstancias del hecho se
apreciarán de acuerdo con el grado de partici-
pación en la comisión de la falta y la existencia
de circunstancias agravantes, atenuantes o
eximentes de responsabilidad.
• Los motivos determinantes se apreciarán según
se haya procedido por innobles o fútiles o por
nobles o altruistas.
• Los antecedentes personales del estudiante.
Se consideran circunstancias agravantes:
• Reincidir en la comisión de faltas.
• Cometer la falta mediante acciones violentas,
ocultando la identidad con capuchas, en tumul-
to, o con la complicidad de personas internas o
ajenas a LA UNIVERSIDAD.
• Cometer la falta aprovechando la confianza
depositada.
• Cometer la falta para ocultar otra.
• No aceptar la responsabilidad o atribuírsela a
otro u otros.
• Infringir varias obligaciones con la misma acción
u omisión.
• Preparar a conciencia la infracción y las moda-
lidades empleadas en la comisión de la misma.
Serán circunstancias atenuantes:
• La buena conducta anterior.
• El haber sido inducido a cometer la falta.
• El confesar la falta oportunamente o evitar la
injusta sindicación de terceros.
• Reconocer voluntaria y autocríticamente la falta.
• Procurar, a iniciativa propia, resarcir el daño o
compensar el perjuicio causado antes de ini-
ciarse el proceso disciplinario.
Serán circunstancias eximentes:
• La ignorancia invencible.
• Caso fortuito o Fuerza mayor.
• Actuar en el estricto cumplimiento de un deber
o la protección de un interés legítimo mayor.
ARTÍCULO 39º. SANCIONES. Las conductas
definidas en los literales b) al p) del artículo 37º causarán
expulsión de LA UNIVERSIDAD, una vez definido el grado
de culpabilidad y la participación en ellas.
Las conductas definidas en el literal a) del artículo
37º podrán ser clasificadas como leves o graves tenien-
do en cuenta los criterios de valoración establecidos
en este Acuerdo. Las sanciones para estas conductas
serán:
Para faltas leves:
• La amonestación escrita o matrícula condicional
por uno o más semestres.
• Suspensión temporal o definitiva de los servicios
e incentivos ofrecidos por LA UNIVERSIDAD.
Para faltas graves:
• Suspensión temporal del derecho a obtener
el título.
• La no proclamación del título en la ceremonia
de grado.
• No renovación de la matrícula para uno o más
períodos académicos.
Parágrafo. Todas las sanciones disciplinarias
serán aplicadas por LA UNIVERSIDAD sin perjuicio de
las sanciones penales a que hubiere lugar.
ARTÍCULO 40º. Cuando el aspirante a ingresar a
LA UNIVERSIDAD utilice para ese efecto cualquier clase
de fraude (aporte de documentos falsos, suministro
de datos falsos, suplantación de personalidad, etc.) no
será sujeto de este régimen disciplinario, pero perderá
definitivamente el derecho a ser admitido, sin perjuicio
de las acciones legales a que haya lugar.
ARTÍCULO 41º. Las sanciones disciplinarias
contempladas en este Reglamento se harán constar
en la historia académica del estudiante. Para ello, la
instancia encargada de comunicar al estudiante la
sanción impuesta remitirá copia de dicha actuación a
la División de Admisiones y Registro.
ARTÍCULO 42º. Los procesos disciplinarios es-
tudiantiles serán de competencia en primera instancia
del consejo del departamento al cual está adscrito el
estudiante, y en segunda instancia del consejo de la
facultad respectiva.
ARTÍCULO 43º. La acción disciplinaria se iniciará
de oficio, por información o queja, debidamente funda-
mentada, de un funcionario o miembro de la comunidad
universitaria, presentada ante la autoridad académica
respectiva.
Parágrafo. La acción disciplinaria y la aplicación
de las sanciones serán procedentes aunque el estu-
diante se haya retirado de LA UNIVERSIDAD. De toda
decisión se dejará constancia en la historia académica
del estudiante.
ARTÍCULO 44º. Contra las decisiones que ponen
fin a una actuación proceden los recursos de reposición
y apelación.
ARTÍCULO 45º. PROCEDIMIENTO DISCIPLI-
NARIO. El procedimiento disciplinario será el siguiente:
• Una vez conocida una situación que pudiese
constituir falta disciplinaria por parte de un es-
tudiante, se pondrá en conocimiento del consejo
de departamento o quien haga sus veces, quien
deberá iniciar el proceso disciplinario profirien-
do una decisión al respecto en la que le hará
saber por escrito al estudiante, dentro de los
tres (3) días hábiles siguientes al conocimiento
del hecho, cuales son sus derechos y sobre la
práctica de las pruebas que estime pertinentes,
de acuerdo con el caso.
• Oídos los descargos y practicadas las pruebas
correspondientes en un término no superior a
diez (10) días hábiles a partir de la comunica-
ción al estudiante, procederá a establecer si la
situación puede calificarse como falta. En caso
positivo, establecerá el grado de responsabilidad
e impondrá la sanción correspondiente, decisión
tomada por la mayoría simple de sus miembros,
la cual constará por escrito.
• El acto a través del cual se impone sanción disci-
plinaria al estudiante es susceptible del recurso
de apelación dentro de los cinco (5) días hábiles
siguientes a la fecha de su notificación. Si esta
no pudiera hacerse de manera personal se hará
mediante publicación en cartelera, durante un
término de tres (3) días hábiles. La apelación se
surtirá ante el consejo de la facultad respectiva
quien, basándose en las pruebas obrantes po-
drá confirmar la decisión, revocarla o disminuirla,
por la mayoría simple de sus miembros, decisión
que será notificada dentro de los cinco (5) días
hábiles a su expedición y contra la misma no
procede ningún recurso.
• De la decisión ejecutoriada se dará traslado a las
áreas competentes para su aplicación.
• De todas las actuaciones que tengan que ver
con el proceso disciplinario se dejará constancia
escrita.
• Las pruebas de inculpación o de defensa allega-
das al proceso disciplinario se apreciarán libre-
mente. Las que sean notoriamente superfluas,
inconducentes o impertinentes, se rechazarán
desde el comienzo.
ARTÍCULO 46°. Este Reglamento no excluye la
aplicación de otras normas establecidas para la utiliza-
ción de los servicios y dependencias de LA UNIVERSI-
DAD, ni suspende las sanciones previstas por ellas para
su buena marcha.
ARTÍCULO 47º. El presente Reglamento rige a
partir de la fecha de su publicación y deroga las normas
que le sean contrarias, en especial el Acuerdo 180 de
1980 del Consejo Superior y los Acuerdos reglamen-
tarios.
PUBLÍQUESE, COMUNIQUESE Y CÚMPLASE
JUANA INÉS DÍAZ TAFUR
Presidenta del Consejo
MARÍA DEL PILAR PÁEZ ALDANA
Secretaria del Consejo

El Consejo Superior reunido en sesión extraordina-
ria los días 9 abril y 4 de mayo de 2012, ha definido la
siguiente directriz:
Directiva No. 01 de 2012
CON VIVENCIAS
Agenda para la vida universitaria
Los últimos acontecimientos que segaron la vida de
tres miembros de nuestra comunidad, dos estudiantes
y una egresada, sumados a las reiteradas y persis-
tentes manifestaciones de violencia en la sede de la
calle 72, llevan a concluir que las condiciones en que
está transcurriendo la vida cotidiana de la Universidad
cuestionan su viabilidad y no ofrecen garantías para
la integridad física y el habitar de sus miembros y de
quienes a ella acuden.
Por ello, se hace necesario proponer una serie de
acciones tendientes a incidir efectivamente sobre la
vida universitaria, así como activar un sistema de alertas
y consolidar mecanismos de protección solidaria de
toda la comunidad que permitan, desde lo que cabe a
la competencia de las autoridades universitarias y de
los docentes -en tanto comunidad académica, parte
de la sociedad civil y maestros que han de orientar a la
juventud que se les ha confiado-, mitigar o suprimir ries-
gos evidentes a los que están expuestos funcionarios,
docentes y estudiantes, particularmente los menores
de edad, adolescentes y población con vulnerabilidad
especial.
En cumplimiento de los anteriores propósitos, es
conveniente proponer una agenda de trabajo, desde
cinco ejes –político, acompañamiento, académico,
cultural y administrativo - que convoque a todos los
actores institucionales, y que se surta a través del
trabajo mancomunado de profesores, estudiantes,
cuerpos colegiados y autoridades académicas, con la
participación de las entidades públicas del ámbito local,
Distrital y Nacional.
Adicionalmente, es preciso estructurar cronogra-
mas, explorar medios y alternativas de financiación,
definir sujetos y colectivos de responsabilidad y asignar
funciones a la mayor brevedad. Esta agenda, que se
desarrollará en los distintos niveles de organización aca-
démica, y que convocará a la comunidad universitaria,
de acuerdo con sus roles y por tanto responsabilidades,
tendrá los siguientes propósitos mínimos:
• Analizar la viabilidad de la Universidad Pedagó-
gica Nacional en un escenario de cohabitación
con la violencia al interior del campus, y generar
e implementar propuestas de acción que per-
mitan preservar condiciones de convivencia
coherentes con la esencia de una institución
de educación superior y con la identidad y el
sentido institucional.
• Desarrollar estrategias para la asunción efectiva
de la responsabilidad de la comunidad docente,
que en ejercicio de una ciudadanía donde se
pone de manifiesto su condición de comuni-
dad intelectual y de su papel orientador como
maestro, promueva en el estudiante el cuidado
de sí y del otro.
• Incorporar eficazmente en la cotidianidad ins-
titucional procesos y protocolos de protección
para toda la comunidad y, en particular con
atención especial, para el menor de edad que
participa de programas de formación regular
formal o de educación continuada, y para
quienes presentan alguna condición especial
de vulnerabilidad o de discapacidad.
Los tópicos fundamentales a desarrollar en la
agenda propuesta son:
Eje de cultura política y construcción de
institucionalidad
Por supuesto que la discusión de la naturaleza,
sentidos, misión y compromisos de la Universidad
Pedagógica no puede sustraerse a las dinámicas y
condiciones que tienen lugar en el escenario nacional,
regional e internacional. Por eso se hace necesario
convocar a la comunidad en su conjunto -a través del
Consejo Académico, las Facultades y con el liderazgo de
las comunidades académicas de pregrado y postgrado-
a conformar mesas de trabajo y adelantar jornadas
periódicas de análisis y elaboración de documentos
en torno a los temas de actualidad que inciden en la
edificación de institucionalidad pública en Educación
Superior, contribuyen a la construcción y despliegue
de subjetividades políticas en el escenario universitario
y coadyuvan a definir los horizontes de actuación y
proyección de la Pedagógica en los escenarios local,
nacional, regional e internacional.
Se propone convocar una primera jornada insti-
tucional de esta naturaleza para el 15 de mayo, día
del maestro y solicitar al Consejo Académico en coor-
dinación con los Consejos de Facultad, establecer la
agenda para llevar a cabo las demás jornadas a lo largo
del año y definir los mecanismos de participación y de
elaboración de documentos que recojan la pluralidad
de análisis, respeten las distintas formas de contribuir
a la actualización del Proyecto Institucional, propicien
el compromiso del mayor número de miembros de la
comunidad y contribuyan a mejorar los sentidos de
pertenencia y las prácticas de convivencia de todos los
miembros de la Universidad.
Eje de fortalecimiento del
acompañamiento a estudiantes
Se trata de incrementar las posibilidades de orien-
tación y apoyo institucional al transcurrir del estudiante
de pregrado por su vida universitaria en las distintas
dimensiones de su formación. Se busca potenciar la
experiencia diversa que la Universidad ha venido cons-
truyendo con cada uno de sus estudiantes a través de
los programas académicos en la figura del coordinador
o del asesor de cohorte, en la interacción personalizada
en la tutoría, en la presencia de actividades culturales
y deportivas, en las prácticas educativas o en el apoyo
desde las distintas instancias de bienestar, de modo
que a lo largo de su progresiva maduración intelectual
y emocional, y de la construcción de autonomía, el
estudiante -que en muchos casos ha ingresado como
menor de edad- experimente la corresponsabilidad de la
comunidad universitaria en su formación y sentimientos
de protección, apoyo y orientación para la concreción de
su realización como profesional de la educación.
El objetivo contempla componentes técnicos, en
tanto es necesario consolidar sistemas de información
ágiles que permitan conocer al estudiante en aspectos
mucho más amplios que el de su desempeño académico
consignado en la DAR, y articular escenarios de apoyo
según las especificidades de las competencias de las
instancias educadoras; así como de naturaleza con-
ceptual sobre los alcances y límites de las acciones de
orientación en el marco de una institución formadora de
educadores, laica, pública y universitaria. Los resultados
de caracterización que se deriven de los procesos de
acompañamiento, en coherencia con el horizonte mi-
sional, han de constituirse en insumo para una revisión
integral de los procesos de admisión.
Por otra parte, en articulación con el mejoramiento
de las actividades de inducción de estudiantes de
primer semestre se reforzará la interacción con padres
de familia y se creará un proceso de inducción para
nuevos docentes.
Se espera que al inicio de segundo semestre acadé-
mico de 2012 se disponga de un esquema operacional
mínimo para incorporar de forma sistemática las ase-
sorías de cohorte a todos los programas de pregrado
y de un conjunto de lineamientos institucionales para
su realización,
evaluación y seguimiento. En el transcurso de este
proceso se contará con el concurso de experiencias de
otras instituciones universitarias que permitan valorar
en sus componentes pedagógicos, logísticos y finan-
cieros el fortalecimiento del programa.
Eje académico
Se refiere al impulso de la reflexión académica
institucional orientada hacia la constitución de mesas
de trabajo sobre los procesos de formación de pregrado
en curso, en referencia a la construcción de ciudadanía,
derechos humanos y convivencia en el marco del ethos
propio de una institución universitaria que aspira a
ser escenario humanista de educación superior, y a
la constitución de cátedras consecuentes de carácter
permanente, que sean incorporadas de forma explícita
a los procesos formativos. También implica la revisión
integral de los ciclos de fundamentación de todos los
programas de pregrado en el marco de la autoevaluación
institucional, el fortalecimiento de los procesos de se-
guimiento y evaluación del ejercicio de la misión docente
tras la explicitación de objetivos específicos de calidad y
rigor académico en un contexto en el que se reconoce el
riesgo de perturbaciones a la normalidad académica, y la
potenciación de la actividad cultural como expresión del
reconocimiento explícito de la importancia fundamental
que para la formación del maestro, -más sin duda que
para otras profesiones-, tiene el entorno en el que
transcurre la vida universitaria.
En relación con el último aspecto cabe la reformu-
lación de estrategias de orientación y de comunicación
en cada espacio académico, que considerando sus par-
ticularidades, mitigue los efectos de los impedimentos
transitorios de las interacciones personales docente-
estudiante en el aula y potencie una respuesta civilista
signada por la inteligencia y la voluntad, en la que se
ejerza tanto la libertad de cátedra del docente como
la autonomía de sujeto en formación del estudiante,
y en los dos casos, se construya ciudadanía de modo
efectivo junto con la conciencia de corresponsabilidad
de docentes, administrativos y estudiantes frente a
la calidad educativa que demanda la sociedad de una
institución formadora de formadores. Con los medios
de comunicación contemporáneos resulta inadmisible
que la convocatoria académica, a la que asisten en
forma cotidiana y libre estudiantes y profesores para
la realización de procesos de formación, devenga en
imposibilidad de concurrencia como si se trátese de
un designio incontrolable. Se pretende ampliar la con-
cepción de presencialidad de nuestros programas e
incorporar estrategias pedagógicas, evaluativas y comu-
nicativas que respondan a los desarrollos tecnológicos
y culturales y a las nuevas necesidades de formación.
Frente a la autoevaluación académica institucional
de la misión docente, el Consejo Académico en primera
instancia, con el concurso de toda la comunidad for-
talecerá una ruta de acompañamiento permanente
a la enseñanza-aprendizaje de pregrado que genere
balances globales construidos detalladamente a partir
de cada espacio académico y en la que en los conceptos
claves del orden educativo sometidos a análisis sean
asumidos explícitamente en forma proyectiva como
horizonte colectivo. La articulación con el proceso
de acreditación y el fortalecimiento de la identidad
institucional se harán evidentes a partir de logros per-
ceptibles derivados de la intencionalidad de un mayor
rigor académico.
Por otra parte, la potenciación de las actividades
académicas extracurriculares permitirá superar insufi-
ciencias de flexibilidad asociadas a la misma estructura
de los planes de estudio y proporcionará escenarios
propios de una institución abierta y viva a las diversas
producciones del espíritu humano. Círculos literarios,
grupos de teatro, de lectura, de cine, de ciencias y
divulgación científica, astronomía, matemática lúdica,
robótica, etc., hacen posible que la Universidad sea
una ventana abierta al mundo por la que circulen los
intereses y motivaciones intelectuales de todos y una
plataforma para proyectar los sueños y aspiraciones
de los más jóvenes hacia el encuentro de un sentido
de vida más pleno.
Antes de iniciar el próximo semestre se dispondrá
de la programación de la primera cátedra y de una
propuesta que contemple el apoyo a los grupos estu-
diantiles arriba enunciados y determine las condiciones
para su reconocimiento y permanencia. Para la misma
fecha se espera un primer balance que consolide la
información que sobre los distintos aspectos descritos
han desarrollado o proyectaran las distintas unidades
académicas. Por otra parte, el Consejo Académico
precisará el cronograma para la revisión normativa ne-
cesaria, que ya se inició con una primera presentación
sobre elementos conceptuales para la modificación del
Reglamento Estudiantil.
Eje de vida cultural y éticas de ciudadanía
La Universidad se comprende hoy y ahora como
un crisol en el que se funden diversas materias que
conforman las historias y las experiencias humanas
para provocar transformaciones, evoluciones de pen-
samiento, lecturas complejas. Un delicado tejido, que en
el ejercicio de la cátedra, debe mantener el necesario
equilibrio para formar seres sociales sin ignorar referen-
tes, contextos y expectativas. En el entendido de que en
la formación de los sujetos se contienen dimensiones
que cruzan campos de conocimiento disciplinar de las
ciencias, las artes, las técnicas; con los referentes y los
contextos; con las biografías de unos y de otros; con los
medios, los lenguajes, los modos, usos y costumbres,
podríamos acompañar la idea de que ese vasto universo
que conforman los entornos educativos y los entornos
vivenciales caben dentro del concepto de cultura.
Como un ser vivo, la universidad precisa incorporar
las transformaciones y las tendencias sociales a su
devenir. La cultura, en su acepción contemporánea,
se ocupa de ello al promover y exaltar diversidades
expresivas, creativas y referenciales. En sus múltiples
implicaciones, la cultura escapa de la academia, pero
se inserta profundamente en ella, ya que la academia
misma es cultura, porque es un espacio de transaccio-
nes formativas.
Este eje, entonces, propone vivir y habitar la uni-
versidad como fenómeno cultural que trasciende los
desarrollos curriculares, que no está circunscrita a un
período específico del calendario, sino que se desarrolla
a lo largo de los ciclos académicos.
Jornadas de las culturas
Las jornadas de las culturas que se instituyen a
partir de 2011 se refieren al momento en el que la uni-
versidad en su conjunto se convoca para participar de
una programación concertada con las diversas unidades
académicas. La programación recoge actividades acadé-
micas y artísticas, usa como escenario todas las sedes
de la universidad, convoca profesionales de diversas
disciplinas -propios e invitados- para desarrollar ciclos
de conferencias, encuentros y simposios alrededor de
ejes temáticos acordados.
Para el presente año se propone como eje el tema
de la convivencia, en la perspectiva de las actitudes, los
gestos y los símbolos desde los discursos provenientes
de las ciencias sociales, las artes y las cotidianidades.
Para la concertación de la programación y el desarrollo
de las actividades se adoptará la misma metodología
del año anterior con los recursos financieros previstos.
Circulación de saberes y experiencias
estéticas
La construcción de referentes y experiencias esté-
ticas se constituye en una oportunidad de configurar
referentes simbólicos comunes mediante estrategias
que permiten situar la formación de maestros como
lugar de encuentro con estéticas sociales y artísticas.
Se propone incorporar dentro de los rituales de
inicio de actividades académicas, cada semestre, la
entrega de un texto, un impreso emblemático de la
tradición humanística literaria, analítica, artística; o de
la producción de pensamiento de nuestra universidad,
que amplíe los referentes de la comunidad estudiantil.
Espacios para la promoción de estéticas
compartidas
En el contexto de la misión institucional, la forma-
ción de educadores es la base de todas las acciones
entre las que se han de situar las dimensiones ética,
estética, social y política. En consecuencia, se parte
de la premisa de lo estético como relacional y la ética
como ejercicio político de responsabilidad social. Juntas
se comunican, se configuran y se forman, lo que implica
legitimar la transacción, la alteridad y la corresponsa-
bilidad en la constitución de procesos de regulación
y autorregulación. Se propone propiciar encuentros
mensuales de muestras artísticas interfacultades en
las distintas sedes de la Universidad y avanzar en la
construcción colectiva de un documento sobre estéticas
compartidas, ética y formación docente.
Articulación a programas de convivencia
ciudadana
La Universidad, históricamente, ha desarrollado
diversos proyectos que tienen su expresión en cátedras,
seminarios, cursos que asumen como uno de los facto-
res comunes la pregunta por las formas de convivencia
en las sociedades contemporáneas, las maneras de
ser y estar en el mundo, como sujetos individuales y
colectivos, pero siempre en relación con otros.
Esto implica considerar las relaciones y los impactos
que la universidad tiene con los entornos, con la ciudad
e incluso con el país. Su rótulo de entidad educadora
pone a la Universidad en una condición sensible y muy
particular al momento de pensar esta relación.
En estas condiciones, se debe trabajar en la arti-
culación de los programas que acogen el asunto de
la convivencia como punto de partida, modernizarlos,
armonizarlos con nuevas iniciativas como una manera
de abrir el dialogo y las acciones afirmativas de recono-
cimiento de la convivencia como factor de estabilidad
institucional. Se desarrollarán seminarios, cátedras,
cursos libres, bajo la idea de la convivencia como un
propósito común en relación con la formación de docen-
tes, con una perspectiva que articule las dimensiones
éticas, políticas, sociales y estéticas como expresión del
compromiso por habitar la ciudad.
En su conjunto, este eje estará coordinado desde
la Vicerrectoría de Gestión Universitaria, la Facultad
de Bellas Artes, la división de Bienestar Universitario y
Extensión Cultural, como se hizo en la experiencia de
las Jornadas de las culturas, en 2011.
Eje de gestión administrativa
Se plantea avanzar en la incorporación de un
conjunto de normas técnicas para el mejoramiento
de la gestión administrativa en materia de prevención
del riesgo para el personal administrativo, profesores,
estudiantes y visitantes e implica el avance en la ade-
cuación física de las áreas comunes de tránsito hasta la
reorganización del talento humano, entre otras.
Se busca adelantar varias acciones que se desarro-
llarían en su implementación básica, de acuerdo con los
recursos que se adicionen al presupuesto general de
2012. Estas acciones pretenden:

Implementar y apropiar de manera integrada los
sistemas de gestión de calidad bajo las normas
ISO9001 y NTCGP 1000, Seguridad en la infor-
mación bajo la Norma ISO 27001, Seguridad y
Salud Ocupacional bajo la Norma OHSAS 18001,
Medio Ambiente bajo la Norma ISO 14001, con
el fin de que estas herramientas optimicen la
gestión institucional. A la par se adoptarán las
recomendaciones del informe final de consul-
toría en convivencia y seguridad (contrato de
prestación de servicios 330 de 2011).
• Mejorar la ruta de acción interinstitucional con
las entidades del ámbito local y distrital para
una gestión preventiva del riesgo en materia de
protección de la integridad física de menores de
edad (ley de Infancia y Adolescencia, 1098 de
2006), población en situación de discapacidad y
demás miembros de la comunidad universitaria
y de terceros; así como la salvaguarda de bienes
públicos y mitigación de la afectación del trán-
sito peatonal y vehicular, entre otros.
• Mejorar la accesibilidad, movilidad y transporte
para personas en situación de discapacidad (Ley
361 de 1997), adoptar una normatividad sobre
usos del espacio público en la Universidad, e
intervenir la infraestructura física de interés
cultural como acción previa a la implementación
del Plan de Regularización y Manejo (Decreto
distrital 904 de 2001).
• Adecuar los espacios físicos y la adquisición de
infraestructura tecnológica para la conversión
y salvaguarda de los archivos y el mejoramiento
de la gestión documental.
• Reorganizar y potenciar el talento humano.
La construcción de escenarios para los ejes descri-
tos se inscribe en el propósito de consolidación de una
política académica que articula la cultura y el bienestar
a un proyecto integral de formación de maestros.
El conjunto de actividades contará con un espacio
de síntesis que propicie el diálogo entre la Universidad
y otras instituciones formadoras, las organizaciones
del magisterio, secretarías de educación, en particular
de Bogotá y Cundinamarca, Ministerio de Educación
Nacional, asociaciones de instituciones de educación
Básica y Media, organizaciones de padres de familia y
otros actores de la sociedad. Se finalizará con un even-
to que, a manera de Foro Nacional por la Universidad
Pedagógica Nacional, contribuya a sopesar y proyectar
la responsabilidad y las acciones que le corresponde
asumir a la UPN en relación con la construcción de
ciudadanía, defensa de los derechos humanos y con-
vivencia pacífica en el marco de un Sistema Nacional de
Formación de Educadores.
Esta actividad se prevé para mediados del segun-
do semestre académico de 2012 y se articulará a la
agenda del proceso de autoevaluación y a la discusión
sobre Reforma de la Ley de Educación Superior que se
promueva en la Universidad.
La Vicerrectoría de Gestión, en coordinación con la
Gerencia de medios, de la que forman parte la División
de Recursos Educativos, el Grupo de Comunicaciones, el
Fondo editorial, desarrollará una estrategia integral de
medios y recursos de divulgación. Esta estrategia inte-
gral incluye su posicionamiento, un plan para recopilar
y divulgar información y un plan de medios.
OTRAS NORMAS
El marco normativo para la construcción de con-
vivencia universitaria es amplio, abarca los Acuerdos
que conforman el régimen estudiantil y académico, y
las reglamentaciones del orden nacional que regulan
políticas sobre protección de la salud en asuntos con-
cretos como el consumo de psicoactivos y prevención
del tabaquismo, entre otros, que deben articularse de
manera armónica con el estatuto legal de la autonomía
universitaria.
La apropiación de este conjunto de disposiciones
que a continuación se relacionan, constituye un com-
promiso institucional de ineludible aceptación en el
currículo de los programas de formación, en tanto es
un elemento central de construcción de identidad con
la misión encomendada por la sociedad a la Universidad,
que no excluye la necesaria discusión y contextualiza-
ción en los distintos escenarios públicos.
Normatividad sobre prohibición de venta
y consumo de psicoativos
El Acuerdo 025 de 3 de agosto de 2007, del Con-
sejo Superior, en su capítulo VIII De la convivencia
universitaria, dispone en el artículo 36 que: “Tal como
establece el Estatuto Académico, los estudiantes como
miembros de la comunidad universitaria actuarán guia-
dos por los principios de ética, responsabilidad, lealtad
y respeto a las personas, las autoridades, los símbolos,
las normas vigentes institucionales, de manera que
sus relaciones con la Universidad se desarrollen bajo
los principios de convivencia universitaria democrática,
participativa y plural, la responsabilidad compartida y el
reconocimiento de sí mismo como sujeto responsable
de su proceso de formación”.
En el artículo 37 del mencionado Acuerdo, se seña-
lan entre otras, como conductas que atentan contra el
orden académico, la Ley, los estatutos y reglamentos:
(…) h) La utilización de las instalaciones de
la Universidad para la venta o comercialización
de cualquier producto o servicio no autorizado
(respecto de la prohibición de la venta de ciga-
rrillos al menudeo o a menores de edad y de su
consumo en la institución educativa. Capítulo I de
la Ley 1335 de 2009
(…) k) la guarda, tráfico y/o consumo de
sustancias psicoactivas o alucinógenas dentro
del recinto universitario
l) El presentarse a la Universidad en o cual-
quier actividad académica, cultural o deportiva
en estado de embriaguez, o bajo el efecto de
cualquier sustancia psicoactiva o alucinógena.
(…) Todas las conductas tipificadas como
delitos por las leyes de la República.
La Universidad debe propender por la eficacia de
su normatividad y por el cumplimiento de las normas
vigentes en pro del interés general de la comunidad
universitaria.
Política antitabaco
Contexto normativo nacional
Mediante la Ley 1109 de 2006, Colombia adoptó
el “Convenio Marco de la Organización Mundial de la
Salud –OMS- para el control del tabaco” con el fin de
proteger a las generaciones presentes y futuras de los
graves estragos que produce el consumo de tabaco o
la exposición al humo del mismo mediante medidas
legislativas, administrativas y ejecutivas eficaces, de
protección contra la exposición al humo de tabaco en
lugares de trabajo interiores, medios de transporte pú-
blico, lugares públicos cerrados y, según proceda, otros
lugares públicos, y promoverá activamente la adopción
y aplicación de esas medidas en otros niveles.
El Gobierno Nacional mediante la Resolución No.
01956 de 2008, por la cual se adoptan medidas en rela-
ción con el consumo de cigarrillo o de tabaco estudió las
consecuencias de la exposición de la población al humo
del cigarrillo y posteriormente, mediante la Ley 1335 de
21 de julio de 2009, dictó: Disposiciones por medio de las
cuales se previenen daños a la salud de los habitantes
del territorio nacional, en especial de los menores de
edad, la población no fumadora y se estipulan políticas
públicas para la prevención del consumo del tabaco y
el abandono de la dependencia del tabaco del fumador
y sus derivados en la población colombiana, con el fin
de contribuir a garantizar los derechos a la salud de los
menores de 18 años de edad y la población no fumadora,
regulando el consumo, venta, publicidad y promoción de
los cigarrillos, tabaco y sus derivados, y se establecen
las sanciones correspondientes a quienes contravengan
las disposiciones de la ley.
La Ley 1335 de 2009 dispone planes de capa-
citación y promoción en los sectores educativos, y
responsables de la formación de menores de edad,
así como a los servidores públicos en general sobre
las consecuencias adversas del consumo de tabaco
e inhalación del humo de tabaco. En el artículo 10 de
esta misma ley se estableció el deber de las entidades
públicas de divulgar esta norma en las páginas elec-
trónicas que tengan habilitadas y en otros medios de
difusión con que cuenten.
El artículo 18 de la Ley 1335 de 2009 comprende
los derechos de las personas no fumadoras entre otros,
los siguientes:
• Respirar aire puro libre de humo de tabaco y
sus derivados.
• Protestar cuando se enciendan cigarrillos,
tabaco y sus derivados en sitios en donde
su consumo se encuentre prohibido por la
presente ley, así como exigir del propietario,
representante legal, gerente, administrador o
responsable a cualquier título del respectivo
negocio o establecimiento, se conmine al o a
los autores de tales conductas a suspender de
inmediato el consumo de los mismos.
• Acudir ante la autoridad competente en defensa
de sus derechos como no fumadora y a exigir la
protección de los mismos.
• Exigir la publicidad masiva de los efectos nocivos
y mortales que produce el tabaco y la exposición
al humo del tabaco.
• Informar a la autoridad competente el incumpli-
miento de lo previsto en la presente ley.
En virtud de la protección de los mencionados de-
rechos se estipuló en el artículo 19 de la mencionada
ley las siguientes prohibiciones:
Prohíbase el consumo de productos de tabaco, en
los lugares señalados en el presente artículo.
• En las áreas cerradas de los lugares de trabajo
y/o de los lugares públicos, tales como: Bares,
restaurantes, centros comerciales, tiendas,
ferias, festivales, parques, estadios, cafeterías,
discotecas, cibercafés, hoteles, ferias, pubs,
casinos, zonas comunales y áreas de espera,
donde se realicen eventos de manera masiva,
entre otras.
• Las entidades de salud.
• Las instituciones de educación formal y no
formal, en todos sus niveles
• Museos y bibliotecas
• Los establecimientos donde se atienden a
menores de edad.
• Los medios de transporte de servicio público,
oficial, escolar, mixto y privado.
• Entidades públicas y privadas destinadas para
cualquier tipo de actividad industrial, comercial
o de servicios, incluidas sus áreas de atención
al público y salas de espera.
• Áreas en donde el consumo de productos de
tabaco generen un alto riesgo de combustión
por la presencia de materiales inflamables,
tal como estaciones de gasolina, sitios de
almacenamiento de combustibles o materiales
explosivos o similares.
• Espacios deportivos y culturales.
Con el fin de tener claridad sobre los lugares de
prohibición de consumo de tabaco y sus derivados el
artículo 21 de la precitada, estableció las definiciones:
Área cerrada: Todo espacio cubierto por un te-
cho y confinado por paredes, independientemente del
material utilizado para el techo, las paredes o los muros
y de que la estructura sea permanente o temporal.
Humo de tabaco ajeno o humo de tabaco
ambiental: El humo que se desprende del extremo
ardiente de un cigarrillo o de otros productos de tabaco
generalmente en combinación con el humo exhalado
por el fumador.
Fumar. El hecho de estar en posición de control
de un producto de tabaco encendido independiente-
mente de que el humo se esté inhalando o exhalando
en forma activa.
Lugar de trabajo: Todos los lugares utilizados
por las personas durante su empleo o trabajo incluyen-
do todos los lugares conexos o anexos y vehículos que
los trabajadores utilizan en el desempeño de su labor.
Esta definición abarca aquellos lugares que son resi-
dencia para unas personas y lugar de trabajo para otras.
Lugares públicos: Todos los lugares accesi-
bles al público en general, o lugares de uso colectivo,
independientemente de quién sea su propietario o del
derecho de acceso a los mismos.
Transporte público: Todo vehículo utilizado
para transportar al público, generalmente con fines
comerciales o para obtener una remuneración. Incluye
a los taxis.
Y el artículo 24 prevé como sanciones por fumar en
sitios o lugares prohibidos:
La infracción a lo dispuesto en el artículo 17 de la
presente normatividad, dará lugar a una amonestación
verbal y a una sanción pedagógica que le obligará a
asistir a un día de capacitación sobre los efectos nocivos
del cigarrillo.
La Policía Nacional junto con el Ministerio de la Pro-
tección Social fijará los elementos y recursos necesarios
para la aplicación de las sanciones establecidas en el
presente artículo.
En consecuencia, la Ley 1335 de 2009 estableció
como obligaciones aplicables a la Universidad, en su
labor de consecución de los fines del Estado (Art. 2
constitución Política de Colombia y de la salvaguarda
de los derechos fundamentales como derecho a gozar
de un ambiente sano Art. 79 de la Constitución Política
de Colombia) las siguientes:
(...) Los propietarios, empleadores y adminis-
tradores de los lugares a los que hace referencia
el artículo 19 de la Ley 1335 de 2009, tienen las
siguientes obligaciones:
• Velar por el cumplimiento de las prohibiciones
establecidas en la presente ley con el fin de
proteger a las personas de la exposición del
humo de tabaco ambiental;
• Fijar en un lugar visible al público avisos que
contengan mensajes alusivos a los ambientes
libres de humo, conforme a la reglamentación
que expida el Ministerio de la Protección Social;
• Adoptar medidas específicas razonables a fin de
disuadir a las personas de que fumen en el lugar,
tales como pedir a la persona que no fume,
interrumpir el servicio, pedirle que abandone
el local o ponerse en contacto con la autoridad
competente.: " . $pregunta;

    $data = json_encode(["contents" => [["parts" => [["text" => $finalPrompt]]]]]);

    $options = [
        "http" => [
            "header"  => "Content-Type: application/json\r\n",
            "method"  => "POST",
            "content" => $data
        ]
    ];

    $context = stream_context_create($options);
    $response = @file_get_contents($url, false, $context);

    if ($response === false) {
        echo json_encode(["error" => "Compa, hubo un error al obtener la respuesta.", "detalle" => error_get_last()]);
    } else {
        $responseData = json_decode($response, true);

        if (isset($responseData["candidates"][0]["content"]["parts"][0]["text"])) {
            echo json_encode(["respuesta" => $responseData["candidates"][0]["content"]["parts"][0]["text"]]);
        } else {
            echo json_encode(["error" => "No se pudo obtener respuesta de la API."]);
        }
    }
}
?>
