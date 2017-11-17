<?php

namespace SigRH\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

/**
 * Formulário utilizado para o cadastro de estagios
 */
class EstagioForm extends Form {

    protected $objectManager;
    /**
     * Construtor
     */
    public function __construct($objectManager) {
        //Determina o nome do formulário
        parent::__construct('estagio-form');

        $this->objectManager = $objectManager;
        //Define o método POST para envio do formulário
        $this->setAttribute('method', 'post');

        $this->addElements();
        $this->addInputFilter();
    }

        public function setObjectManager(ObjectManager $objectManager) {
        $this->objectManager = $objectManager;
    }

    public function getObjectManager() {
        return $this->objectManager;
    }
    protected function addElements() {
        //Adiciona o campo "descricao"
        $this->add([
            'type' => 'text',
            'name' => 'anoInicio',
            'attributes' => [
                'id' => 'anoInicio',
                'class' => 'form-control',
                'placeholder' => 'Digite o ano de início aqui'
            ],
            'options' => [
                'label' => 'Ano de início'
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'anoPrevisaoConclusao',
            'attributes' => [
                'id' => 'anoPrevisaoConclusao',
                'class' => 'form-control',
                'placeholder' => 'Digite a previsão de conclusão aqui'
            ],
            'options' => [
                'label' => 'Previsão de conclusão'
            ],
        ]);

        //Adiciona o campo "nivel"
        $this->add([
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'name' => 'nivel',
            'attributes' => [
                'id' => 'nivel',
                'class' => 'form-control',
                'placeholder' => 'Digite o nível aqui'
            ],
            'options' => [
                'label' => 'Nivel',
                'empty_option' => 'Selecione',
                'object_manager' => $this->getObjectManager(),
                'target_class' => \SigRH\Entity\Nivel::class,
                'property' => 'descricao',
                'display_empty_item' => true,
            ]
        ]);

        //Adiciona o campo "curso"
        $this->add([
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'name' => 'curso',
            'attributes' => [
                'id' => 'curso',
                'class' => 'form-control',
                'placeholder' => 'Digite o curso aqui'
            ],
            'options' => [
                'label' => 'Curso',
                'empty_option' => 'Selecione',
                'object_manager' => $this->getObjectManager(),
                'target_class' => \SigRH\Entity\Curso::class,
                'property' => 'descricao',
                'display_empty_item' => true,
            ]
        ]);
        
        //Adiciona o campo "instituicao"
        $this->add([
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'name' => 'instituicao',
            'attributes' => [
                'id' => 'instituicao',
                'class' => 'form-control',
            ],
            'options' => [
                'label' => 'Instituição',
                'empty_option' => 'Selecione',
                'object_manager' => $this->getObjectManager(),
                'target_class' => \SigRH\Entity\Instituicao::class,
                'property' => 'desRazaoSocial',
                
                'find_method' => [
                    'name' => 'getQuery',
                    'params' => [
                        'search' => [
                            'combo' => 1
                        ]
                    ]
                ],
                
                
//                'property' => 'ListParaCombo',
//                'display_empty_item' => true,
            ]
        ]);

        
        //Adiciona o campo "serie"
        $this->add([
            'type' => 'text',
            'name' => 'serie',
            'attributes' => [
                'id' => 'serie'
            ],
            'options' => [
                'label' => 'Série'
            ],
        ]);
        
        
       
        //Adiciona o campo "dataInicioEfetivo"
        $this->add([
            'type' => 'Zend\Form\Element\Date',
            'name' => 'dataInicioEfetivo',
            'attributes' => [
                'id' => 'dataInicioEfetivo',
                'class' => 'form-control',
            ],
            'options' => [
                'format' => 'd/m/Y',
                'label' => 'Data início efetivo'
            ],
        ]);
        
        
        //Adiciona o campo "fonteSeguro"
        $this->add([
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'name' => 'fonteSeguro',
            'attributes' => [
                'id' => 'fonteSeguro',
                'class' => 'form-control',
                'placeholder' => 'Digite a fonte seguro aqui'
            ],
            'options' => [
                'label' => 'Fonte seguro',
                'empty_option' => 'Selecione',
                'object_manager' => $this->getObjectManager(),
                'target_class' => \SigRH\Entity\FonteSeguro::class,
                'property' => 'descricao',
                'display_empty_item' => true,
            ]
        ]);
        
        //Adiciona o campo "seguroApolice"
        $this->add([
            'type' => 'text',
            'name' => 'seguroApolice',
            'attributes' => [
                'id' => 'seguroApolice'
            ],
            'options' => [
                'label' => 'Seguro apólice'
            ],
        ]);
        
        
        //Adiciona o campo "seguroSeguradora"
        $this->add([
            'type' => 'text',
            'name' => 'seguroSeguradora',
            'attributes' => [
                'id' => 'seguroSeguradora'
            ],
            'options' => [
                'label' => 'Seguradora'
            ],
        ]);
        
        //Adiciona o campo "seguroMensalidade"
        $this->add([
            'type' => 'text',
            'name' => 'seguroMensalidade',
            'attributes' => [
                'id' => 'seguroMensalidade'
            ],
            'options' => [
                'label' => 'Mensalidade seguro'
            ],
        ]);
        
        //Adiciona o campo "seguroCapital"
        $this->add([
            'type' => 'text',
            'name' => 'seguroCapital',
            'attributes' => [
                'id' => 'seguroCapital'
            ],
            'options' => [
                'label' => 'Seguro capital'
            ],
        ]);
        

        $this->add([
            'type' => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Salvar',
                'id' => 'submitbutton',
            ]
        ]);
    }

    private function addInputFilter() {
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        $inputFilter->add([
            'name' => 'anoInicio',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => 'StripNewlines'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 2,
                        'max' => 10
                    ],
                ],
            ],
        ]);
        
        
         $inputFilter->add([
            'name' => 'anoPrevisaoConclusao',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => 'StripNewlines'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 200
                    ],
                ],
            ],
        ]);

         $inputFilter->add([
            'name' => 'nivel',
            'required' => true,
        ]);
       
         $inputFilter->add([
            'name' => 'curso',
            'required' => true,
        ]);
         
         $inputFilter->add([
            'name' => 'instituicao',
            'required' => false,
        ]);
         
         
         $inputFilter->add([
            'name' => 'serie',
            'required' => false,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => 'StripNewlines'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 2,
                        'max' => 200
                    ],
                ],
            ],
        ]);
         
         $inputFilter->add([
            'name' => 'dataInicioEfetivo',
            'required' => false,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => 'StripNewlines'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 2,
                        'max' => 200
                    ],
                ],
            ],
        ]);
   
         $inputFilter->add([
            'name' => 'fonteSeguro',
            'required' => true,
        ]);
         
         $inputFilter->add([
            'name' => 'seguroApolice',
            'required' => false,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => 'StripNewlines'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 200
                    ],
                ],
            ],
        ]);

         $inputFilter->add([
            'name' => 'seguroSeguradora',
            'required' => false,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => 'StripNewlines'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 200
                    ],
                ],
            ],
        ]);
         
         $inputFilter->add([
            'name' => 'seguroMensalidade',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => 'StripNewlines'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 200
                    ],
                ],
            ],
        ]);
         
         $inputFilter->add([
            'name' => 'seguroCapital',
            'required' => false,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => 'StripNewlines'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 200
                    ],
                ],
            ],
        ]);
    }

}
