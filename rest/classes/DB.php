<?php
  /**
   * DB-Class=> handles connections/querys
   *
   * @author  David Krawiec
   * @date    20.09.2017
   */
  class DB {
    private $DB;
    private $DB_NAME = "bountytask";

    public function __construct($db_ip, $db_name, $db_user=null, $db_pass=null){
        if(isset($db_user) && isset($db_pass)){
            $this->DB = new MongoDB\Driver\Manager("mongodb://" . $db_user . ':' . $db_pass . '@' . $db_ip);
        }
        else{
            $this->DB = new MongoDB\Driver\Manager("mongodb://" . $db_ip . ':27017');
        }
        $this->initCollections();
    }

    public function connection_established(){
        if($this->DB != null)
            return(true);
        return(false);
    }

    public function initCollections(){
        // first we create the collections; database is created
        // implicit
        $cmd = new MongoDB\Driver\Command(
            [
                "create" => "user",
                "validator" => [
                    '$or' => array(
                        [
                            "date"=> ['$exists' => true, '$type' => "date"],
                            "name"=> ['$exists'=> true, '$type' => 'string'],
                            "score"=> ['$exists' => true, '$type' => 'int'],
                            "status"=> ['$exists' => true, '$in' => ['idle', 'offline', 'online']],
                            "public_info"=> ['$exists' => true, '$type' => 'string'],
                            "tasks_ids"=> ['$exists' => true, '$type' => 'array'],
                            "groups_ids"=> ['$exists' => true, '$type' => 'array'],
                            "messages_ids"=> ['$exists' => true, '$type' => 'array']
                        ]
                    )
                ],
                'validationAction' => 'warn'
            ]
        );
        $this->DB->executeCommand($this->DB_NAME, $cmd);
        $cmd = new MongoDB\Driver\Command(
            [
                "create" => "group",
                "validator" => [
                    '$or' => array(
                        [
                            "date"=> ['$exists' => true, '$type' => "date"],
                            "name"=> ['$exists'=> true, '$type' => 'string'],
                            "score"=> ['$exists' => true, '$type' => 'int'],
                            "description"=> ['$exists' => true, '$type' => 'string'],
                            "public_info"=> ['$exists' => true, '$type' => 'string'],
                            "images_ids"=> ['$exists' => true, '$type' => 'array'],
                            "users_ids"=> ['$exists' => true, '$type' => 'array'],
                            "messages_ids"=> ['$exists' => true, '$type' => 'array']
                        ]
                    )
                ],
                'validationAction' => 'warn'
            ]
        );
        $this->DB->executeCommand($this->DB_NAME, $cmd);
        $cmd = new MongoDB\Driver\Command(
            [
                "create" => "image",
                "validator" => [
                    '$or' => array(
                        [
                            "date"=> ['$exists' => true, '$type' => "date"],
                            "url"=> ['$exists'=> true, '$type' => 'string'],
                            "description"=> ['$exists' => true, '$type' => 'string'],
                        ]
                    )
                ],
                'validationAction' => 'warn'
            ]
        );
        $this->DB->executeCommand($this->DB_NAME, $cmd);
        // TODO: it would be a good idea to put message: from and to as index
        $cmd = new MongoDB\Driver\Command(
            [
                "create" => "message",
                "validator" => [
                    '$or' => array(
                        [
                            "date"=> ['$exists' => true, '$type' => "date"],
                            "from"=> ['$exists'=> true, '$type' => 'string'],
                            "to"=> ['$exists' => true, '$type' => 'string'],
                            "content"=> ['$exists' => true, '$type' => 'string'],
                        ]
                    )
                ],
                'validationAction' => 'warn'
            ]
        );
        $this->DB->executeCommand($this->DB_NAME, $cmd);
        $cmd = new MongoDB\Driver\Command(
            [
                "create" => "task",
                "validator" => [
                    '$or' => array(
                        [
                            "date"=> ['$exists' => true, '$type' => "date"],
                            "creator_id"=> ['$exists'=> true, '$type' => 'string'],
                            "description"=> ['$exists' => true, '$type' => 'string'],
                            "messages_ids"=> ['$exists' => true, '$type' => 'array'],
                            "users_ids"=> ['$exists' => true, '$type' => 'array'],
                            "images_ids"=> ['$exists' => true, '$type' => 'array'],
                            "geo"=> ['$exists' => true, '$type' => 'array'],
                            "bountys_ids"=> ['$exists' => true, '$type' => 'array'],
                            "validators_ids"=> ['$exists' => true, '$type' => 'array'],
                        ]
                    )
                ],
                'validationAction' => 'warn'
            ]
        );
        $this->DB->executeCommand($this->DB_NAME, $cmd);
        $cmd = new MongoDB\Driver\Command(
            [
                "create" => "bounty",
                "validator" => [
                    '$or' => array(
                        [
                            "date"=> ['$exists' => true, '$type' => "date"],
                            "payers_id"=> ['$exists'=> true, '$type' => 'string'],
                            "type"=> ['$exists' => true, '$type' => 'string'],
                            "amount"=> ['$exists' => true, '$type' => 'float'],
                            "payment_api_details"=> ['$exists' => true, '$type' => 'array'],
                        ]
                    )
                ],
                'validationAction' => 'warn'
            ]
        );
        $this->DB->executeCommand($this->DB_NAME, $cmd);
    }

    /////////////////
    // USER-QUERYS //
    ////////////////

    public function user_add($oUser){
        // TODO: get first element of ids; as we only 1 user at once
        $oUser['_id'] = new MongoDB\BSON\ObjectId();
        $bulk = new MongoDB\Driver\BulkWrite();
        $bulk->insert($oUser);

        $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
        $writeResult = $this->DB->executeBulkWrite($this->DB_NAME . '.user', $bulk, $writeConcern);

        return($oUser);
    }

    public function user_get($name, $pass){

    }

    public function user_delete($_id){

    }

    public function user_update($_id, $oUser){

    }

    /////////////////
    // TASK-QUERYS //
    ////////////////

    public function task_add($oTask){
    }

    public function task_get(){
    }

    public function task_delete($_id){
    }

    public function task_update($_id, $oTask){
    }

  }
?>
