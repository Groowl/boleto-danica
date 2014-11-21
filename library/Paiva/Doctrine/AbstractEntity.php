<?php
/**
 * PAIVA
 * Biblioteca de apoio para o desenvolvimento em Zend Framework
 * 
 * --
 * 
 * @author     Marcio Paiva Barbosa <mpaivabarbosa@gmail.com>
 * @copyright  2011 Marcio Paiva Barbosa <mpaivabarbosa@gmail.com>
 * @version    1.0.1 (2011-12-30)
 * @license    http://opensource.org/licenses/mit-license.php MIT License  
 * @link       https://github.com/marciopaiva/zf1-paiva
 */
namespace Paiva\Doctrine;

use \Zend_Registry,
    Doctrine\DBAL\LockMode;

abstract class AbstractEntity {

    /**
     * Get the doctrine container
     * 
     * @return Paiva\Doctrine\Container
     */
    private static function getContainer() {
        return \Zend_Registry::get('Paiva_Doctrine');
    }

    /**
     * Get the entity manager.
     * 
     * @param string $emName
     * @return Doctrine\ORM\EntityManager
     */
    public static function getEntityManager($emName = null) {
        return self::getContainer()->getEntityManager($emName);
    }

    /**
     * Get the connection manager.
     * 
     * @param string $emName
     * @return Doctrine\ORM\EntityManager
     */    
    public static function getConnection($emName = null) {
        return self::getEntityManager($emName)->getConnection();
    }


    /**
     * Get the entity repository.
     * 
     * @param string $emName
     * @return Doctrine\ORM\EntitiyRepository
     */
    public static function getRepository($emName = null) {
        $entityName = \get_called_class();
        return self::getEntityManager($emName)->getRepository($entityName);
    }

    /**
     * Finds an Entity by its identifier.
     *
     * This is just a convenient shortcut for getRepository($entityName)->find($id).
     *
     * @param mixed $identifier
     * @param string $emName 
     * @param int $lockMode
     * @param int $lockVersion
     * @return object
     */
    public function find( $identifier, $emName = null, $lockMode = LockMode::NONE, $lockVersion = null)
    {
        return self::getRepository($emName)->find($identifier, $lockMode, $lockVersion);
    }  
    
    /**
     * Finds all entities in the repository.
     *
     * This is just a convenient shortcut for getRepository($entityName)->findAll().
     *
     * @param string $emName 
     * @return array The entities.
     */
    public function findAll($emName = null) {
        return self::getRepository($emName)->findAll();
    }

    /**
     * Finds entities by a set of criteria.
     *
     * This is just a convenient shortcut for getRepository($entityName)->findBy().
     * 
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @param string $emName 
     * @return array The objects.
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null, $emName = null)
    {
        return self::getRepository($emName)->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * Finds a single entity by a set of criteria.
     * 
     * This is just a convenient shortcut for getRepository($entityName)->findOneBy().
     *
     * @param array $criteria
     * @param string $emName 
     * @return object
     */
    public function findOneBy(array $criteria, $emName = null)
    {
        return self::getRepository($emName)->findOneBy($criteria);
    }    
    

    /**
     * Removes the entity from persistant storage.
     * 
     * @param string $emName 
     * @param boolean $flush
     * @return true
     */
    public function remove($emName = null, $flush = true)
    {
        $em = self::getEntityManager($emName);
        $em->remove($this);
        
        if ($flush) {
            $em->flush();
        }
        
        return true;
    }

    /**
     * Save the entity to persistant storage.
     * 
     * @param string $emName 
     * @param boolean $flush
     * @return true
     */
    public function save($emName = null, $flush = true)
    {
        $em = self::getEntityManager($emName);
        $em->persist($this);
        
        if ($flush) {
            $em->flush();
        }
        
        return true;
    }  
   
  
}