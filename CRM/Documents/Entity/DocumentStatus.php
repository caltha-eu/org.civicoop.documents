<?php

/* 
 * This class return status values of a document
 * We have only two statusses at the moment
 * - unused (the document is not in use by a case or contact)
 * - used (the document is in use by a case or a contact)
 */

class CRM_Documents_Entity_DocumentStatus {
  
  const UNUSED = 0; 
  const USED = 1;
  
  
  /**
   * We only need one instance of this object. So we use the singleton
   * pattern and cache the instance in this variable
   *
   * @var object
   * @access private
   * @static
   */
  static private $_singleton = NULL;
  
  protected function __construct() {
    
  }
  
  /**
   * Constructor and getter for the singleton instance
   *
   * @return instance of $config->userHookClass
   */
  static function singleton($fresh = FALSE) {
    if (self::$_singleton == NULL || $fresh) {
      self::$_singleton = new CRM_Documents_Entity_DocumentStatus();
    }
    return self::$_singleton;
  }
  
  /**
   * Returns the status of a documents
   * 
   * @param CRM_Documents_Entity_Document $doc
   */
  public function getStatusOfDocument(CRM_Documents_Entity_Document $doc) {
    
    $hookStatus = CRM_Documents_Entity_DocumentStatus::UNUSED;
    $hooks = CRM_Utils_Hook::singleton();
    $hooks->invoke(2,
      $doc, $hookStatus, CRM_Utils_Hook::$_nullObject, CRM_Utils_Hook::$_nullObject, CRM_Utils_Hook::$_nullObject,
      'civicrm_documents_get_status'
      );
    
    $status = CRM_Documents_Entity_DocumentStatus::UNUSED;
    
    if ($hookStatus == CRM_Documents_Entity_DocumentStatus::USED) {
      $status = CRM_Documents_Entity_DocumentStatus::USED;
    } elseif (count($doc->getContactIds())) {
      $status = CRM_Documents_Entity_DocumentStatus::USED;
    } elseif (count($doc->getCaseIds())) {
      $status = CRM_Documents_Entity_DocumentStatus::USED;
    } elseif (count($doc->getEntities())) {
      $status = CRM_Documents_Entity_DocumentStatus::USED;
    }
    
    return $status;
    
  }
}

