<?php

class Implantation {

  public $db;

  function __construct($db) {
    $this->db = $db;
  }

  function getImplantationInformation($id) {
    /*
    (IN) $id(int): id of the implantation for which we want to collect the data
    (OUT) array with information / false if no implantation was found
    */

    try {

      $statement = $this->db->prepare(
        "SELECT
        `I`.`nameimplantation` as `name`,
        `I`.`streetimplantation` as `street`,
        `I`.`postalCodeimplantation` as `postalCode`,
        `I`.`cityimplantation` as `city`,
        `C`.`nameCountryEnglish` as `country`,
        `I`.`countryCodeimplantation` as `codeCountry`

        FROM `implantation` as `I`
        LEFT JOIN `country` as `C` ON `I`.`countryCodeimplantation` = `C`.`codeCountry`

        WHERE idImplantation = :idImplantation

        LIMIT 0,1");

      $statement->bindParam(':idImplantation', $id, PDO::PARAM_INT);
      $statement->execute();

      if($statement->rowCount()){
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        return $row;
      } else {
        return false;
      }

    } catch (PDOException $e) {
      print "Error !: " . $e->getMessage() . "<br/>";
      die();
    }

  }

  function getImplantationCount() {
    /*
    (OUT) int with number of implantation / 0 if no implantation was found
    */
    try {

      $statement = $this->db->prepare("SELECT COUNT(`I`.`idImplantation`) as `number` FROM `implantation` as `I`");
      $statement->execute();

      if($statement->rowCount() > 0) {
        $data = $statement->fetch(PDO::FETCH_ASSOC);
        return intval($data['number']);
      } else {
        return 0;
      }

    } catch (PDOException $e) {
      print "Error !: " . $e->getMessage() . "<br/>";
      die();
    }
  }

  function getImplantationList($start = 0, $number = 25, $orderBy = 'nameimplantation', $orderDir = 'ASC') {

    try {

      $row = array();
      $statement = $this->db->prepare(
        "SELECT
        `I`.`nameimplantation` as `name`,
        `I`.`streetimplantation` as `street`,
        `I`.`postalCodeimplantation` as `postalCode`,
        `I`.`cityimplantation` as `city`,
        `C`.`nameCountryEnglish` as `country`,
        `I`.`countryCodeimplantation` as `codeCountry`

        FROM `implantation` as `I`
        LEFT JOIN `country` as `C` ON `I`.`countryCodeimplantation` = `C`.`codeCountry`

        ORDER BY  `I`.`".$orderBy."` ".$orderDir."

        LIMIT ".$start.",".$number);
      $statement->execute();

      if($statement->rowCount() > 0) {
        while ( $en = $statement->fetch(PDO::FETCH_ASSOC) ) {
          array_push($row, $en);
        }
        return $row;
      } else {
        return false;
      }

    } catch (PDOException $e) {
      print "Error !: " . $e->getMessage() . "<br/>";
      die();
    }

  }

}

?>