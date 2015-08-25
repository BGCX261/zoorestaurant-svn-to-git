<?php

/**
 * Singleton que genera la configuración para los bucadores.
 *
 *  
 */
class AsyncSearch {
	
	private static $instance;
	
	/**
	 * Devuelve la instancia del singleton
	 *
	 * @return AsyncSearch
	 */
	public static function getInstance() {
		if (!self::$instance) {
			self::$instance = new AsyncSearch();
		}
		return self::$instance;
	}
	
	/**
	 * Devuelve al configuración del buscador de institutiones
	 *
	 * @return AsyncRecordFinder
	 */
	public function mozos($mozos = null) {
		$recordFinder = new AsyncRecordFinder('Buscar Mozo', Restaurant::getInstance()->mozos());
		$recordFinder->addSearchFieldFree('nombre', 'Nombre');
		$recordFinder->addSearchFieldFree('apellido', 'Apellido');
		$recordFinder->addTitleField('nombre', 'Nombre');
		$recordFinder->addTitleField('apellido', 'Apellido');
		return $recordFinder->generateXmlConfiguration();
	}
	
	public function productos($mozos = null) {
		$recordFinder = new AsyncRecordFinder('Buscar Producto', Restaurant::getInstance()->productos());
		$recordFinder->addSearchFieldFree('codigo', 'Codigo');
		$recordFinder->addSearchFieldFree('nombre', 'Nombre');
		$recordFinder->addTitleField('codigo', 'Codigo');
		$recordFinder->addTitleField('nombre', 'Nombre');
		return $recordFinder->generateXmlConfiguration();
	}
	
	public function onDemandProductos() {
		$recordFinder = new DoctrineOnDemandAsyncRecordFinder('Buscar Producto','Producto');
		$recordFinder->addSearchFieldFree('codido', 'Codigo', 2);
		$recordFinder->addSearchFieldFree('nombre', 'Nombre', 3);
		$recordFinder->addTitleField('codigo', 'Codigo');
		$recordFinder->addTitleField('nombre', 'Nombre');
		$recordFinder->addDescriptionField('descripcion', 'Descripcion');
				
		return $recordFinder;
	}
	
	
	/**
	 * Devuelve la configuracion del buscador de médicos
	 */
	public function doctors(){
		$recordFinder = new AsyncRecordFinder('Search doctor', LaboratorySystem::getInstance()->doctors());
		$recordFinder->addSearchFieldFree('surname', 'Surname');
		$recordFinder->addSearchFieldFree('first_name', 'Name');
		$recordFinder->addSearchFieldFree('licence_number', 'Licence number');		
		$recordFinder->addTitleField('surname', 'Surname');
		$recordFinder->addTitleField('first_name', 'Name');
		$recordFinder->addDescriptionField('surname', 'Surname');
		$recordFinder->addDescriptionField('first_name', 'Name');		
				
		return $recordFinder->generateXmlConfiguration();
		
	}
	
	/**
	 * Protocol institution
	 *
	 * @return AsyncRecordFinder
	 */
	public function protocolInstitutions($institutions = null) {
		if (!$institutions) {
			$institutions = LaboratorySystem::getInstance()->institutions();
		}
		$recordFinder = new AsyncRecordFinder('Search institutions', $institutions);
		$recordFinder->addSearchFieldFree('Institution', 'Institution');
		$recordFinder->addTitleField('Institution', 'Institution');
		return $recordFinder->generateXmlConfiguration();
	}
	
	
	/**
	 * Devuelve al configuración del buscador de protocolos
	 *
	 * @return AsyncRecordFinder
	 */
	public function protocols() {
		$recordFinder = new AsyncRecordFinder('Search protocols', LaboratorySystem::getInstance()->protocols());
		$recordFinder->addSearchFieldFree('code', 'Code');
		$recordFinder->addSearchFieldFree('name', 'Name');
		$recordFinder->addTitleField('code', 'Code');
		$recordFinder->addTitleField('name', 'Name');
		$recordFinder->addDescriptionField('description', 'Description');
		$recordFinder->addDescriptionField('Sponsor', 'Sponsor');
				
		return $recordFinder->generateXmlConfiguration();
	}
	
	/**
	 * Devuelve al configuración del buscador de obras sociales
	 *
	 * @return AsyncRecordFinder
	 */
	public function insuranceCompanies() {
		$recordFinder = new AsyncRecordFinder('Search insurance companies', LaboratorySystem::getInstance()->insuranceCompanies());
		$recordFinder->addSearchFieldFree('code', 'Code');
		$recordFinder->addSearchFieldFree('name', 'Name');
		$recordFinder->addTitleField('code', 'Code');
		$recordFinder->addTitleField('name', 'Name');
		$recordFinder->addDescriptionField('description', 'Description');
				
		return $recordFinder->generateXmlConfiguration();
	}
	
	/**
	 * Devuelve al configuración del buscador de planes de una obra social
	 * @param InsuranceCompany $company
	 * @return AsyncRecordFinder
	 */
	public function plans($company) {
		$recordFinder = new AsyncRecordFinder('Search plans', $company->insurancePlans());
		$recordFinder->addSearchFieldFree('code', 'Code');
		$recordFinder->addSearchFieldFree('name', 'Name');
		$recordFinder->addTitleField('code', 'Code');
		$recordFinder->addTitleField('name', 'Name');
		$recordFinder->addDescriptionField('description', 'Description');
				
		return $recordFinder->generateXmlConfiguration();
	}
	
	/**
	 * Devuelve al configuración del buscador de pacientes confidenciales
	 *
	 * @return AsyncRecordFinder
	 */
	public function confidentialPatients() {
		$recordFinder = new AsyncRecordFinder('Search patients', LaboratorySystem::getInstance()->patients());
		$recordFinder->addSearchFieldFree('code', 'Code');
		$recordFinder->addSearchFieldFree('sex', 'Sex');
		$recordFinder->addSearchFieldFree('age', 'Age');
		$recordFinder->addSearchFieldFree('initialis', 'Initials');
		//FIXME Hay un problema con esto, hay que arreglar el módulo js. 
		//$recordFinder->addDescriptionField(new AsyncSearchDisplayField('sex', 'Sex'));
		//$recordFinder->addDescriptionField(new AsyncSearchDisplayField('age', 'Age'));
		
		return $recordFinder->generateXmlConfiguration();
	}


	/**
	 * Devuelve al configuración del buscador de pacientes confidenciales
	 *
	 * @return AsyncRecordFinder
	 */
	public function services() {
		$recordFinder = new AsyncRecordFinder('Search Services', LaboratorySystem::getInstance()->services());
		$recordFinder->addSearchFieldFree('code', 'Code');
		$recordFinder->addSearchFieldFree('name', 'Name');
		$recordFinder->addTitleField('code', 'Code');
		$recordFinder->addTitleField('name', 'Name');
		$recordFinder->addDescriptionField('name', 'Name');
		return $recordFinder->generateXmlConfiguration();
	}

	/**
	 * Devuelve la configuración del buscador de Pacientes
	 *
	 * @return AsyncRecordFinder
	 */
	public function generalPatients() {
		$recordFinder = new AsyncRecordFinder('Search Patients', LaboratorySystem::getInstance()->generalPatients(false));
		$recordFinder->addTitleField('identifier', 'Identifier');
		
		$recordFinder->addSearchFieldFree('codesString', 'Code');
		$recordFinder->addSearchFieldFree(array('person', 'document'), 'Document');
		$recordFinder->addSearchFieldFree(array('person', 'fullName'), 'Name');
		$recordFinder->addSearchFieldFree(array('person', 'birthDate'), 'Birth date');
		$recordFinder->addTitleField('deleted', 'Marked as deleted');	
		return $recordFinder->generateXmlConfiguration();
	}

		
	
	/**
	 * Devuelve la configuración del buscador de Registros de Paciente
	 *
	 * @return AsyncRecordFinder
	 */
	public function registers() {
		$recordFinder = new AsyncRecordFinder('Search Patient Registers', LaboratorySystem::getInstance()->patientRegisters()->add(LaboratorySystem::getInstance()->getDefaultPatientRegister()));
		$recordFinder->addSearchFieldFree('code', 'Code');
		$recordFinder->addSearchFieldFree('name', 'Name');
		$recordFinder->addTitleField('code', 'Code');
		$recordFinder->addTitleField('name', 'Name');	
		$recordFinder->addDescriptionField('name', 'Name');		
		return $recordFinder->generateXmlConfiguration();
	}	
	
	/**
	 * Devuelve al configuración del buscador de órdenes de clinical trials
	 *
	 * @return AsyncRecordFinder
	 */
	public function orders() {
		$recordFinder = new AsyncRecordFinder('Search Orders', LaboratorySystem::getInstance()->orders());
		$recordFinder->addSearchFieldFree('access_number', 'Access number');
		$recordFinder->addSearchFieldFree('Patient', 'Patient');
		$recordFinder->addTitleField('access_number', 'Access number');
		$recordFinder->addDescriptionField('date', 'Date');
		return $recordFinder->generateXmlConfiguration();
	}
	

	/**
	 * Devuelve al configuración del buscador de órdenes
	 *
	 * @return AsyncRecordFinder
	 */
	public function clinicalTrialOrders() {
		$recordFinder = new AsyncRecordFinder('Search Orders', LaboratorySystem::getInstance()->clinicalTrialOrders());
		$recordFinder->addSearchFieldFree('access_number', 'Access number');
		$recordFinder->addSearchFieldFree('Patient', 'Patient');
		$recordFinder->addTitleField('access_number', 'Access number');
		$recordFinder->addDescriptionField('date', 'Date');
		return $recordFinder->generateXmlConfiguration();
	}
	
	/**
	 * Devuelve al configuración del buscador de observaciones
	 *
	 * @return AsyncRecordFinder
	 */
	public function observations() {
		$recordFinder = new AsyncRecordFinder('Search Observations', LaboratorySystem::getInstance()->patientObservations());
		$recordFinder->addSearchFieldFree('code', 'Code');
		$recordFinder->addSearchFieldFree('description', 'Description');
		$recordFinder->addTitleField('code', 'Code');
		$recordFinder->addTitleField('description', 'Description');
		$recordFinder->addDescriptionField('requireComment', 'Require Comment');
		return $recordFinder->generateXmlConfiguration();
	}
	
	/**
	 * Devuelve al configuración del buscador de diagnósticos
	 *
	 * @return AsyncRecordFinder
	 */
	public function diagnoses() {
		$recordFinder = new AsyncRecordFinder('Search Diagnoses', LaboratorySystem::getInstance()->diagnoses());
		$recordFinder->addSearchFieldFree('code', 'Code');
		$recordFinder->addSearchFieldFree('description', 'Description');
		$recordFinder->addTitleField('code', 'Code');
		$recordFinder->addTitleField('description', 'Description');
		return $recordFinder->generateXmlConfiguration();
	}	

	/**
	 * Devuelve la configuración del buscador de ciudades
	 *
	 * @return AsyncRecordFinder
	 */
	public function cities() {
		$recordFinder = new AsyncRecordFinder('Search Cities', LaboratorySystem::getInstance()->cities());
		$recordFinder->addSearchFieldFree('name', 'Name');
		return $recordFinder->generateXmlConfiguration();
	}
	
	/**
	 * Devuelve la configuración del buscador de terminales que tienen impresora de etiquetas
	 *
	 * @return AsyncRecordFinder
	 */
	public function labelPrinterTerminals() {
		$recordFinder = new AsyncRecordFinder('Terminal', LaboratorySystem::getInstance()->labelPrinterTerminals());
		$recordFinder->addSearchFieldFree('name', 'Name');
		$recordFinder->addSearchFieldFree('ip', 'IP');
		$recordFinder->addTitleField('ip', 'IP');
		$recordFinder->addTitleField('name', 'Name');
		return $recordFinder->generateXmlConfiguration();
	}
	
/**
	 * Devuelve al configuración del buscador de protocolos dinamico
	 *
	 * @return AsyncRecordFinder
	 */
	public function onDemandProtocols() {
		$recordFinder = new DoctrineOnDemandAsyncRecordFinder('Search Protocols','Protocol');
		$recordFinder->addSearchFieldFree('code', 'Code', 2);
		$recordFinder->addSearchFieldFree('name', 'Name', 3);
		$recordFinder->addTitleField('code', 'Code');
		$recordFinder->addTitleField('name', 'Name');
		$recordFinder->addDescriptionField('description', 'Description');
		$recordFinder->addDescriptionField('Sponsor', 'Sponsor');
				
		return $recordFinder;
	}
	
	public function onDemandOrders() {
		$recordFinder = new DoctrineOnDemandAsyncRecordFinder('Search Orders','ClinicalTrialOrder');
		$recordFinder->addSearchFieldFree('access_number', 'Access number', 3);
		$recordFinder->addTitleField('access_number', 'Access number');
		$recordFinder->addDescriptionField('date', 'Date');
		$recordFinder->addDescriptionField('Protocol', 'Protocol');
		$recordFinder->addDescriptionField('Patient', 'Patient');
		
		return $recordFinder;
	}
	
	public function onDemandServices() {
		$recordFinder = new DoctrineOnDemandAsyncRecordFinder('Search Services','LaboratoryService');
		$recordFinder->addSearchFieldFree('code', 'Code', 2);
		$recordFinder->addSearchFieldFree('name', 'Name', 3);
		$recordFinder->addTitleField('code', 'Code');
		$recordFinder->addTitleField('name', 'Name');
		$recordFinder->addDescriptionField('name', 'Name');
		
		return $recordFinder;
	}
	
	public function onDemandVisits() {
		$recordFinder = new DoctrineOnDemandAsyncRecordFinder('Search Visit', 'Visit');
		$recordFinder->addSearchFieldFree(array('Protocol', 'code'), 'Protocol', 2);
		$recordFinder->addSearchFieldFree('name', 'Name', 3);
		$recordFinder->addTitleField('code', 'Protocol Code');
		$recordFinder->addTitleField('name', 'Name');
		$recordFinder->addDescriptionField('name', 'Name');
		$recordFinder->addDescriptionField('protocol', 'Protocol');
		
		return $recordFinder;
	}

        /**
	 * Devuelve la configuración del buscador de secciones
	 *
	 * @return AsyncRecordFinder
	 */
	public function laboratorySections() {
		$recordFinder = new AsyncRecordFinder('Terminal', LaboratorySystem::getInstance()->laboratoriesSections());
		$recordFinder->addSearchFieldFree('name', 'Name');
		$recordFinder->addTitleField('name', 'Name');
		return $recordFinder->generateXmlConfiguration();
	}

        /**
	 * Devuelve la configuración del buscador de secciones
	 *
	 * @return AsyncRecordFinder
	 */
	public function outpatientReceptionCenters() {
		$recordFinder = new AsyncRecordFinder('Terminal', LaboratorySystem::getInstance()->outpatientReceptionCenters());
		$recordFinder->addSearchFieldFree('name', 'Name');
		$recordFinder->addTitleField('name', 'Name');
		return $recordFinder->generateXmlConfiguration();
	}
	
	/**
	 * Devuelve al configuración del buscador de analysis
	 *
	 * @return AsyncRecordFinder
	 */
	public function onDemandAnalysis() {
		$recordFinder = new DoctrineOnDemandAsyncRecordFinder('Search Analysis', 'LaboratoryAnalysis');
		$recordFinder->addSearchFieldFree('code', 'Code', 2);
		$recordFinder->addSearchFieldFree('name', 'Name', 3);
		$recordFinder->addTitleField('code', 'Code');
		$recordFinder->addTitleField('name', 'Name');
		$recordFinder->addDescriptionField('name', 'Name');
		return $recordFinder;
	}
	
	
}

?>
