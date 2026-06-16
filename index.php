<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/src/db.php';

$app = AppFactory::create();
$app->setBasePath('/APIAL20006');


$app->addBodyParsingMiddleware();


$app->get('/doctores', function (Request $request, Response $response) {
    $sql = "SELECT * FROM Doctores";
    try {
        $db = new Db();
        $db = $db->connect();
        $stmt = $db->query($sql);
  
        $doctores = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        $response->getBody()->write(json_encode($doctores));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } catch (\PDOException $e) {
        $error = array("message" => $e->getMessage());
        $response->getBody()->write(json_encode($error));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
});


$app->post('/doctores', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    
    $sql = "INSERT INTO Doctores (IdDoctor, Nombres, Apellidos, Especialidad, TurnoAtencion, PacientesMinDiarios, NSueldo, IdHospital) 
            VALUES (:IdDoctor, :Nombres, :Apellidos, :Especialidad, :TurnoAtencion, :PacientesMinDiarios, :NSueldo, :IdHospital)";
            
    try {
        $db = new Db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        
        $stmt->bindParam(':IdDoctor', $data['IdDoctor']);
        $stmt->bindParam(':Nombres', $data['Nombres']);
        $stmt->bindParam(':Apellidos', $data['Apellidos']);
        $stmt->bindParam(':Especialidad', $data['Especialidad']);
        $stmt->bindParam(':TurnoAtencion', $data['TurnoAtencion']);
        $stmt->bindParam(':PacientesMinDiarios', $data['PacientesMinDiarios']);
        $stmt->bindParam(':NSueldo', $data['NSueldo']);
        $stmt->bindParam(':IdHospital', $data['IdHospital']);
        
        $stmt->execute();
        
        $result = array("message" => "Doctor agregado con éxito");
        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    } catch (\PDOException $e) {
        $error = array("message" => $e->getMessage());
        $response->getBody()->write(json_encode($error));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
});


$app->post('/hospitales', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    
    $sql = "INSERT INTO Hospitales (IdHospital, NomHospital, CapacidadAtencion, Especialidades) 
            VALUES (:IdHospital, :NomHospital, :CapacidadAtencion, :Especialidades)";
            
    try {
        $db = new Db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        
        $stmt->bindParam(':IdHospital', $data['IdHospital']);
        $stmt->bindParam(':NomHospital', $data['NomHospital']);
        $stmt->bindParam(':CapacidadAtencion', $data['CapacidadAtencion']);
        $stmt->bindParam(':Especialidades', $data['Especialidades']);
        
        $stmt->execute();
        
        $result = array("message" => "Hospital agregado con éxito");
        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    } catch (\PDOException $e) {
        $error = array("message" => $e->getMessage());
        $response->getBody()->write(json_encode($error));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
});


$app->get('/hospitales/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $sql = "SELECT * FROM Hospitales WHERE IdHospital = :id";
    try {
        $db = new Db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        // Corregido usando fetch() y \PDO::FETCH_ASSOC ya que es un único registro por ID
        $hospital = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if($hospital) {
            $response->getBody()->write(json_encode($hospital));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $response->getBody()->write(json_encode(array("message" => "Hospital no encontrado")));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    } catch (\PDOException $e) {
        $error = array("message" => $e->getMessage());
        $response->getBody()->write(json_encode($error));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
});

$app->run();
