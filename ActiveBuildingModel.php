<?php
require_once("globals.php");

class ActiveBuildingModel extends STIXModel {

  function __construct($name, $uid) {
    global $STIX_TYPE_SDO;
    global $STIX_TYPE_SRO;
    global $STIX_TYPE_SCO;
    global $STIX_TYPE_SBO;
    
    $this->id_stix_model = $this->insert($name, $uid);
    
    // Collect all IDs, useful for relationships and references
    $uuids = array();
    
    // Generate elements
    $type = "infrastructure";
    $name = "Main structure";
    $uuid = $this->generate_uuid($type);
    (new STIXObject())->insert($STIX_TYPE_SDO, $name, $uid, $uuid, json_encode(array(
        'type' => $type
      , 'id' => $uuid
      , 'name' => $name
      , 'description' => "This is the Active Building #1"
      , 'objects' => []
    ), JSON_PRETTY_PRINT), $this->id_stix_model);
    $uuids = array_merge($uuids, [ $name => $uuid ]);
    
    $type = "infrastructure";
    $name = "Auxiliary structure";
    $uuid = $this->generate_uuid($type);
    (new STIXObject())->insert($STIX_TYPE_SDO, $name, $uid, $uuid, json_encode(array(
        'type' => $type
      , 'id' => $uuid
      , 'name' => $name
      , 'description' => "This is the Active Building #2"
      , 'objects' => []
    ), JSON_PRETTY_PRINT), $this->id_stix_model);
    $uuids = array_merge($uuids, [ $name => $uuid ]);
    
    $type = "infrastructure";
    $name = "EV Charger";
    $uuid = $this->generate_uuid($type);
    (new STIXObject())->insert($STIX_TYPE_SDO, $name, $uid, $uuid, json_encode(array(
        'type' => $type
      , 'id' => $uuid
      , 'name' => $name
      , 'description' => "Capacity: 20kw-h"
    ), JSON_PRETTY_PRINT), $this->id_stix_model);
    $uuids = array_merge($uuids, [ $name => $uuid ]);

    $type = "infrastructure";
    $name = "Smart Meter at Greenwich";
    $uuid = $this->generate_uuid($type);
    (new STIXObject())->insert($STIX_TYPE_SDO, $name, $uid, $uuid, json_encode(array(
        'type' => $type
      , 'id' => $uuid
      , 'name' => $name
      , 'aliases' => [
          "SmartMeter1"
        ]
    ), JSON_PRETTY_PRINT), $this->id_stix_model);
    $uuids = array_merge($uuids, [ $name => $uuid ]);

    $type = "infrastructure";
    $name = "Solar Array at Greenwich";
    $uuid = $this->generate_uuid($type);
    (new STIXObject())->insert($STIX_TYPE_SDO, $name, $uid, $uuid, json_encode(array(
        'type' => $type
      , 'id' => $uuid
      , 'name' => $name
      , 'description' => "Solar array limited capacity"
      , 'aliases' => [
          "SolarArray#1"
        ]
    ), JSON_PRETTY_PRINT), $this->id_stix_model);
    $uuids = array_merge($uuids, [ $name => $uuid ]);

    $type = "infrastructure";
    $name = "Solar Array at Woolwich";
    $uuid = $this->generate_uuid($type);
    (new STIXObject())->insert($STIX_TYPE_SDO, $name, $uid, $uuid, json_encode(array(
        'type' => $type
      , 'id' => $uuid
      , 'name' => $name
      , 'aliases' => [
          "SolarArray#2"
        ]
    ), JSON_PRETTY_PRINT), $this->id_stix_model);
    $uuids = array_merge($uuids, [ $name => $uuid ]);

    $type = "infrastructure";
    $name = "Smart Meter at Woolwich";
    $uuid = $this->generate_uuid($type);
    (new STIXObject())->insert($STIX_TYPE_SDO, $name, $uid, $uuid, json_encode(array(
        'type' => $type
      , 'id' => $uuid
      , 'name' => $name
      , 'aliases' => [
          "SmartMeter#2"
        ]
    ), JSON_PRETTY_PRINT), $this->id_stix_model);
    $uuids = array_merge($uuids, [ $name => $uuid ]);

    $type = "infrastructure";
    $name = "Greenwich Building";
    $uuid = $this->generate_uuid($type);
    (new STIXObject())->insert($STIX_TYPE_SDO, $name, $uid, $uuid, json_encode(array(
        'type' => $type
      , 'id' => $uuid
      , 'name' => $name
      , 'region' => [
          "europe"
        ]
      , 'country' => "England"
    ), JSON_PRETTY_PRINT), $this->id_stix_model);
    $uuids = array_merge($uuids, [ $name => $uuid ]);

    $type = "location";
    $name = "Woolwich Building";
    $uuid = $this->generate_uuid($type);
    (new STIXObject())->insert($STIX_TYPE_SDO, $name, $uid, $uuid, json_encode(array(
        'type' => $type
      , 'id' => $uuid
      , 'name' => $name
      , 'region' => [
          "europe"
        ]
      , 'country' => "England"
    ), JSON_PRETTY_PRINT), $this->id_stix_model);
    $uuids = array_merge($uuids, [ $name => $uuid ]);

    $type = "location";
    $name = "Solar array in 4th floor";
    $uuid = $this->generate_uuid($type);
    (new STIXObject())->insert($STIX_TYPE_SDO, $name, $uid, $uuid, json_encode(array(
        'type' => $type
      , 'id' => $uuid
      , 'name' => $name
      , 'region' => [
          "europe"
        ]
      , 'country' => "England"
    ), JSON_PRETTY_PRINT), $this->id_stix_model);
    $uuids = array_merge($uuids, [ $name => $uuid ]);

    $type = "observed-data";
    $name = "Observed Data";
    $uuid = $this->generate_uuid($type);
    (new STIXObject())->insert($STIX_TYPE_SDO, $name, $uid, $uuid, json_encode(array(
        'type' => $type
      , 'id' => $uuid
      , 'confidence' => "5"
      , 'first_observed' => "2021-11-12T16:32:43Z"
      , 'last_observed' => "2021-11-12T16:32:43Z"
      , 'number_observed' => "1"
      , 'object_refs' => [
          $uuids["Network Traffic"]
        ]
    ), JSON_PRETTY_PRINT), $this->id_stix_model);
    $uuids = array_merge($uuids, [ $name => $uuid ]);

    $type = "vulnerability";
    $name = "Windows vulnerability 1";
    $uuid = $this->generate_uuid($type);
    (new STIXObject())->insert($STIX_TYPE_SDO, $name, $uid, $uuid, json_encode(array(
        'type' => $type
      , 'id' => $uuid
      , 'confidence' => "10"
      , 'name' => $name
    ), JSON_PRETTY_PRINT), $this->id_stix_model);
    $uuids = array_merge($uuids, [ $name => $uuid ]);

    $type = "infrastructure";
    $name = "viciUS-rootkit";
    $uuid = $this->generate_uuid($type);
    (new STIXObject())->insert($STIX_TYPE_SDO, $name, $uid, $uuid, json_encode(array(
        'type' => $type
      , 'id' => $uuid
      , 'name' => $name
      , 'description' => "Targets .dll in windows machines."
      , 'malware_types' => [
          "backdoor"
        ]
      , 'aliases' => [
          "vicius-kit"
        ]
      , 'architecture_execution_envs' => [
          "x86"
        ]
    ), JSON_PRETTY_PRINT), $this->id_stix_model);
    $uuids = array_merge($uuids, [ $name => $uuid ]);

    $type = "threat-actor";
    $name = "APT Group 1";
    $uuid = $this->generate_uuid($type);
    (new STIXObject())->insert($STIX_TYPE_SDO, $name, $uid, $uuid, json_encode(array(
        'type' => $type
      , 'id' => $uuid
      , 'name' => $name
      , 'description' => "Group acting from US."
      , 'threat_actor_types' => [
          "crime-syndicate"
            ]
      , 'aliases' => [
          "APT#1#"
        ]
      , 'roles' => [
          "agent"
        ]
      , 'sophistication' => [
          "none"
        ]
      , 'resource_level' => [
          "team"
        ]
      , 'primary_motivation' => [
          "accidental"
        ]
    ), JSON_PRETTY_PRINT), $this->id_stix_model);
    $uuids = array_merge($uuids, [ $name => $uuid ]);

    $type = "identity";
    $name = "1tone_of_shade";
    $uuid = $this->generate_uuid($type);
    (new STIXObject())->insert($STIX_TYPE_SDO, $name, $uid, $uuid, json_encode(array(
        'type' => $type
      , 'id' => $uuid
      , 'name' => $name
      , 'identity_class' => [
          "individual"
        ]
      , 'sectors' => [
          "energy"
        ]
    ), JSON_PRETTY_PRINT), $this->id_stix_model);
    $uuids = array_merge($uuids, [ $name => $uuid ]);

    $type = "campaign";
    $name = "Campaign to deploy rootkit";
    $uuid = $this->generate_uuid($type);
    (new STIXObject())->insert($STIX_TYPE_SDO, $name, $uid, $uuid, json_encode(array(
        'type' => $type
      , 'id' => $uuid
      , 'name' => $name
      , 'first-seen' => "2021-11-12T16:50:14Z"
      , 'last-seen' => "2021-11-12T16:50:14Z"
      , 'objective' => "Impact frequency."
    ), JSON_PRETTY_PRINT), $this->id_stix_model);
    $uuids = array_merge($uuids, [ $name => $uuid ]);

    $type = "note";
    $name = "Note";
    $uuid = $this->generate_uuid($type);
    (new STIXObject())->insert($STIX_TYPE_SDO, $name, $uid, $uuid, json_encode(array(
        'type' => $type
      , 'id' => $uuid
      , 'abstract' => "Observation about the campaign to influence frequency."
      , 'content' => "The first level is to compromise the telecommunications network."
      , 'object_refs' => [
          $uuids["Campaign to deploy rootkit"]
        ]
    ), JSON_PRETTY_PRINT), $this->id_stix_model);
    $uuids = array_merge($uuids, [ $name => $uuid ]);

    $type = "relationship";
    $name = "";
    $uuid = $this->generate_uuid($type);
    (new STIXObject())->insert($STIX_TYPE_SRO, $name, $uid, $uuid, json_encode(array(
        'type' => $type
      , 'id' => $uuid
      , 'relationship_type' => "controls"
      , 'source_ref' => $uuids["Main structure"]
      , 'target_ref' => $uuids["EV Charger"]
      , 'start_time' => "2021-11-12T15:07:14Z"
      , 'stop_time' => "2021-11-12T15:07:14Z"
    ), JSON_PRETTY_PRINT), $this->id_stix_model);
    $uuids = array_merge($uuids, [ $name => $uuid ]);

    $type = "relationship";
    $name = "";
    $uuid = $this->generate_uuid($type);
    (new STIXObject())->insert($STIX_TYPE_SRO, $name, $uid, $uuid, json_encode(array(
        'type' => $type
      , 'id' => $uuid
      , 'relationship_type' => "controls"
      , 'source_ref' => $uuids["Main structure"]
      , 'target_ref' => $uuids["Smart Meter at Greenwich"]
      , 'start_time' => "2021-11-12T16:21:13Z"
      , 'stop_time' => "2021-11-12T16:21:13Z"
    ), JSON_PRETTY_PRINT), $this->id_stix_model);
    $uuids = array_merge($uuids, [ $name => $uuid ]);

    $type = "relationship";
    $name = "";
    $uuid = $this->generate_uuid($type);
    (new STIXObject())->insert($STIX_TYPE_SRO, $name, $uid, $uuid, json_encode(array(
        'type' => $type
      , 'id' => $uuid
      , 'relationship_type' => "controls"
      , 'source_ref' => $uuids["Main structure"]
      , 'target_ref' => $uuids["Solar Array at Greenwich"]
      , 'start_time' => "2021-11-12T16:21:51Z"
      , 'stop_time' => "2021-11-12T16:21:51Z"
    ), JSON_PRETTY_PRINT), $this->id_stix_model);
    $uuids = array_merge($uuids, [ $name => $uuid ]);

    $type = "relationship";
    $name = "";
    $uuid = $this->generate_uuid($type);
    (new STIXObject())->insert($STIX_TYPE_SRO, $name, $uid, $uuid, json_encode(array(
        'type' => $type
      , 'id' => $uuid
      , 'relationship_type' => "communicates with"
      , 'source_ref' => $uuids["Main structure"]
      , 'target_ref' => $uuids["Auxiliary structure"]
      , 'start_time' => "2021-11-12T16:25:11Z"
      , 'stop_time' => "2021-11-12T16:25:11Z"
    ), JSON_PRETTY_PRINT), $this->id_stix_model);
    $uuids = array_merge($uuids, [ $name => $uuid ]);

    $type = "relationship";
    $name = "";
    $uuid = $this->generate_uuid($type);
    (new STIXObject())->insert($STIX_TYPE_SRO, $name, $uid, $uuid, json_encode(array(
        'type' => $type
      , 'id' => $uuid
      , 'relationship_type' => "communicates with"
      , 'source_ref' => $uuids["Auxiliary structure"]
      , 'target_ref' => $uuids["Main structure"]
      , 'start_time' => "2021-11-12T16:25:47Z"
      , 'stop_time' => "2021-11-12T16:25:47Z"
    ), JSON_PRETTY_PRINT), $this->id_stix_model);
    $uuids = array_merge($uuids, [ $name => $uuid ]);

    $type = "relationship";
    $name = "";
    $uuid = $this->generate_uuid($type);
    (new STIXObject())->insert($STIX_TYPE_SRO, $name, $uid, $uuid, json_encode(array(
        'type' => $type
      , 'id' => $uuid
      , 'relationship_type' => "controls"
      , 'source_ref' => $uuids["Auxiliary structure"]
      , 'target_ref' => $uuids["Smart Meter at Woolwich"]
      , 'start_time' => "2021-11-12T16:26:10Z"
      , 'stop_time' => "2021-11-12T16:26:10Z"
    ), JSON_PRETTY_PRINT), $this->id_stix_model);
    $uuids = array_merge($uuids, [ $name => $uuid ]);

    $type = "relationship";
    $name = "";
    $uuid = $this->generate_uuid($type);
    (new STIXObject())->insert($STIX_TYPE_SRO, $name, $uid, $uuid, json_encode(array(
        'type' => $type
      , 'id' => $uuid
      , 'relationship_type' => "controls"
      , 'source_ref' => $uuids["Auxiliary structure"]
      , 'target_ref' => $uuids["Solar Array at Woolwich"]
      , 'start_time' => "2021-11-12T16:26:41Z"
      , 'stop_time' => "2021-11-12T16:26:41Z"
    ), JSON_PRETTY_PRINT), $this->id_stix_model);
    $uuids = array_merge($uuids, [ $name => $uuid ]);

    $type = "relationship";
    $name = "";
    $uuid = $this->generate_uuid($type);
    (new STIXObject())->insert($STIX_TYPE_SRO, $name, $uid, $uuid, json_encode(array(
        'type' => $type
      , 'id' => $uuid
      , 'relationship_type' => "located at"
      , 'source_ref' => $uuids["Main structure"]
      , 'target_ref' => $uuids["Greenwich Building"]
      , 'start_time' => "2021-11-12T16:28:46Z"
      , 'stop_time' => "2021-11-12T16:28:46Z"
    ), JSON_PRETTY_PRINT), $this->id_stix_model);
    $uuids = array_merge($uuids, [ $name => $uuid ]);

    $type = "relationship";
    $name = "";
    $uuid = $this->generate_uuid($type);
    (new STIXObject())->insert($STIX_TYPE_SRO, $name, $uid, $uuid, json_encode(array(
        'type' => $type
      , 'id' => $uuid
      , 'relationship_type' => "located at"
      , 'source_ref' => $uuids["Auxiliary structure"]
      , 'target_ref' => $uuids["Woolwich Building"]
      , 'start_time' => "2021-11-12T16:29:16Z"
      , 'stop_time' => "2021-11-12T16:29:16Z"
    ), JSON_PRETTY_PRINT), $this->id_stix_model);
    $uuids = array_merge($uuids, [ $name => $uuid ]);

    $type = "relationship";
    $name = "";
    $uuid = $this->generate_uuid($type);
    (new STIXObject())->insert($STIX_TYPE_SRO, $name, $uid, $uuid, json_encode(array(
        'type' => $type
      , 'id' => $uuid
      , 'relationship_type' => "located at"
      , 'source_ref' => $uuids["Solar Array at Greenwich"]
      , 'target_ref' => $uuids["Solar array in 4th floor"]
      , 'start_time' => "2021-11-12T16:31:53Z"
      , 'stop_time' => "2021-11-12T16:31:53Z"
    ), JSON_PRETTY_PRINT), $this->id_stix_model);
    $uuids = array_merge($uuids, [ $name => $uuid ]);

    $type = "relationship";
    $name = "";
    $uuid = $this->generate_uuid($type);
    (new STIXObject())->insert($STIX_TYPE_SRO, $name, $uid, $uuid, json_encode(array(
        'type' => $type
      , 'id' => $uuid
      , 'relationship_type' => "consists of"
      , 'source_ref' => $uuids["Smart Meter at Greenwich"]
      , 'target_ref' => $uuids["Observed Data"]
      , 'start_time' => "2021-11-12T16:33:25Z"
      , 'stop_time' => "2021-11-12T16:33:25Z"
    ), JSON_PRETTY_PRINT), $this->id_stix_model);
    $uuids = array_merge($uuids, [ $name => $uuid ]);

    $type = "relationship";
    $name = "";
    $uuid = $this->generate_uuid($type);
    (new STIXObject())->insert($STIX_TYPE_SRO, $name, $uid, $uuid, json_encode(array(
        'type' => $type
      , 'id' => $uuid
      , 'relationship_type' => "has"
      , 'source_ref' => $uuids["Main structure"]
      , 'target_ref' => $uuids["Windows vulnerability 1"]
      , 'start_time' => "2021-11-12T16:41:46Z"
      , 'stop_time' => "2021-11-12T16:41:46Z"
    ), JSON_PRETTY_PRINT), $this->id_stix_model);
    $uuids = array_merge($uuids, [ $name => $uuid ]);

    $type = "relationship";
    $name = "";
    $uuid = $this->generate_uuid($type);
    (new STIXObject())->insert($STIX_TYPE_SRO, $name, $uid, $uuid, json_encode(array(
        'type' => $type
      , 'id' => $uuid
      , 'relationship_type' => "exploits"
      , 'source_ref' => $uuids["viciUS-rootkit"]
      , 'target_ref' => $uuids["Windows vulnerability 1"]
      , 'start_time' => "2021-11-12T16:45:37Z"
      , 'stop_time' => "2021-11-12T16:45:37Z"
    ), JSON_PRETTY_PRINT), $this->id_stix_model);
    $uuids = array_merge($uuids, [ $name => $uuid ]);

    $type = "relationship";
    $name = "";
    $uuid = $this->generate_uuid($type);
    (new STIXObject())->insert($STIX_TYPE_SRO, $name, $uid, $uuid, json_encode(array(
        'type' => $type
      , 'id' => $uuid
      , 'relationship_type' => "authored by"
      , 'source_ref' => $uuids["viciUS-rootkit"]
      , 'target_ref' => $uuids["APT Group 1"]
      , 'start_time' => "2021-11-12T16:48:16Z"
      , 'stop_time' => "2021-11-12T16:48:16Z"
    ), JSON_PRETTY_PRINT), $this->id_stix_model);
    $uuids = array_merge($uuids, [ $name => $uuid ]);

    $type = "relationship";
    $name = "";
    $uuid = $this->generate_uuid($type);
    (new STIXObject())->insert($STIX_TYPE_SRO, $name, $uid, $uuid, json_encode(array(
        'type' => $type
      , 'id' => $uuid
      , 'relationship_type' => "impersonates"
      , 'source_ref' => $uuids["APT Group 1"]
      , 'target_ref' => $uuids["1tone_of_shade"]
      , 'start_time' => "2021-11-12T16:49:29Z"
      , 'stop_time' => "2021-11-12T16:49:29Z"
    ), JSON_PRETTY_PRINT), $this->id_stix_model);
    $uuids = array_merge($uuids, [ $name => $uuid ]);

    $type = "relationship";
    $name = "";
    $uuid = $this->generate_uuid($type);
    (new STIXObject())->insert($STIX_TYPE_SRO, $name, $uid, $uuid, json_encode(array(
        'type' => $type
      , 'id' => $uuid
      , 'relationship_type' => "uses"
      , 'source_ref' => $uuids["Campaign to deploy rootkit"]
      , 'target_ref' => $uuids["viciUS-rootkit"]
      , 'start_time' => "2021-11-12T16:50:54Z"
      , 'stop_time' => "2021-11-12T16:50:54Z"
    ), JSON_PRETTY_PRINT), $this->id_stix_model);
    $uuids = array_merge($uuids, [ $name => $uuid ]);

    $type = "network-traffic";
    $name = "Network Traffic";
    $uuid = $this->generate_uuid($type);
    (new STIXObject())->insert($STIX_TYPE_SCO, $name, $uid, $uuid, json_encode(array(
        'type' => $type
      , 'id' => $uuid
      , 'start' => "2021-11-10T16:53:47Z"
      , 'end' => "2021-11-12T16:53:47Z"
      , 'protocols' => [
            "HTTP"
          , "HTTPS"
        ]
    ), JSON_PRETTY_PRINT), $this->id_stix_model);
    $uuids = array_merge($uuids, [ $name => $uuid ]);
    
    // Generate the two active buildings containing the elements
    $type = "grouping";
    $uuid = $this->generate_uuid($type);
    $name = "Active Building #1";
    (new STIXObject())->insert($STIX_TYPE_SDO, $name, $uid, $uuid, json_encode(array(
        'type' => $type
      , 'id' => $uuid
      , 'name' => $name
      , 'context' => "Management of $name"
      , 'object_refs' => []
    ), JSON_PRETTY_PRINT), $this->id_stix_model);
    $uuids = array_merge($uuids, [ $name => $uuid ]);

    $uuid = $this->generate_uuid($type);
    $name = "Active Building #2";
    (new STIXObject())->insert($STIX_TYPE_SDO, $name, $uid, $uuid, json_encode(array(
        'type' => $type
      , 'id' => $uuid
      , 'name' => $name
      , 'context' => "Management of $name"
      , 'object_refs' => []
    ), JSON_PRETTY_PRINT), $this->id_stix_model);
    $uuids = array_merge($uuids, [ $name => $uuid ]);
  }
}
