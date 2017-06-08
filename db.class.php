<?php
/**
 * @author: Szymon Kargol
 */
class db
{
    private $dbh;
    private $stmt;

    function __construct($db)
    {
        $dsn = 'mysql:dbname='.$db['name'].';host='.$db['host'];
        $user = $db['user'];
        $password = $db['password'];//37JMEd8LjD5x7aTa

        try {
            $this->dbh = new PDO($dsn, $user, $password);
        } catch (PDOException $e) {
            die('Błąd bazy danych');
        }
    }

    function saveLog()
    {
        $logData = array(
            date('Y.m.d H:i:s'),
            $_SERVER['REMOTE_ADDR'],
            $_SERVER['HTTP_USER_AGENT']
        );
        $sq = $this->dbh->prepare('INSERT INTO `visit_log` (`id`, `datetime`, `ip`, `user_agent`) VALUES (NULL, :datetime, :ip, :user_agent)');
        $sq->execute(array('datetime' => $logData[0], 'ip' => $logData[1], 'user_agent' => $logData[2]));
    }

    function getLog()
    {
        $limit = 10;
        $offset = 0;
        if(isset($_GET['page']) && is_numeric($_GET['page'])) {
            $offset = $limit*($_GET['page']-1);
        }
        $sq = $this->dbh->prepare('SELECT * FROM `visit_log` ORDER BY `datetime` DESC LIMIT '.$offset.', '.$limit);
        $sq->execute();
        return $sq->fetchAll();
    }

    function getDomains($id=NULL)
    {
        if(is_null(($id)))
            $sq = $this->dbh->prepare('SELECT * FROM `domains`');
        else
            $sq = $this->dbh->prepare('SELECT * FROM `domains` WHERE `id`='.$id);
        $sq->execute();
        return $sq->fetchAll();
    }

    function updateDomain()
    {
        $sq = $this->dbh->prepare('UPDATE `domains` SET `name`=:name, `redirect`=:redirect WHERE `id`=:id');
        $sq->execute(array('name'=>$_POST['domainName'], 'redirect'=>$_POST['domainRedirect'], 'id'=>$_POST['domainId']));
        return $sq->rowCount() ? true : false;
    }

    function insertDomain()
    {
        $sq = $this->dbh->prepare('INSERT INTO `domains` (`id`, `datetime`, `name`, `redirect`) VALUES (NULL, now(), :name, :redirect)');
        $sq->execute(array('name'=>$_POST['domainName'], 'redirect'=>$_POST['domainRedirect']));
        return $sq->rowCount() ? true : false;
    }

    function deleteDomain()
    {
        $sq = $this->dbh->prepare('DELETE FROM `domains` WHERE `id`=:id');
        $sq->execute(array('id'=>$_POST['domainId']));
        return $sq->rowCount() ? true : false;
    }
}