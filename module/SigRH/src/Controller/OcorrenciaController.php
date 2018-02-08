<?php

namespace SigRH\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use SigRH\Entity\Colaborador;
use SigRH\Entity\Ocorrencia;

class OcorrenciaController extends AbstractActionController {

    /**
     * Entity Manager
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * Construtor da classe, utilizado para injetar as dependências no controller
     */
    public function __construct($entityManager) {
        $this->entityManager = $entityManager;
    }

    public function indexAction() {
        
    }

    public function gerarAction() {
        $request = $this->getRequest();
        if ($request->isGet()) {
            $dataInicio = $this->params()->fromQuery('dataInicio');
            $dataTermino = $this->params()->fromQuery('dataTermino');

            if (($dataInicio != "") && ($dataTermino != "")) {

                $dataPesquisaInicial = \DateTime::createFromFormat("Y-m-d", $dataInicio);
                $dataPesquisaFinal = \DateTime::createFromFormat("Y-m-d", $dataTermino);
                $dataPesquisaFinal->setTime(0, 0);
                $dataPesquisaFinal->setTime(0, 0);

                $repoColaborador = $this->entityManager->getRepository(Colaborador::class);

                //busca estagiarios de graduacao
                $colaboradores = $repoColaborador->getEstagiarios(true);

                $colaborador = $this->entityManager->find(\SigRH\Entity\Colaborador::class, '503361');

//                    foreach($colaboradores as $colaborador) {
                $dataPesquisa = $dataPesquisaInicial;

                error_log("COLABORADOR: " . $colaborador->getMatricula() . " - " . $colaborador->getNome());

                $repo = $this->entityManager->getRepository(Ocorrencia::class);

                while ((int) $dataPesquisa->format('Ymd') <= (int) $dataPesquisaFinal->format('Ymd')) {
                    $diaSemana = $dataPesquisa->format("w");

                    error_log("Data: " . $dataPesquisa->format("d-m-Y"));

                    //busca a escala de horarios do colaborador
                    $escala = null;
                    foreach ($colaborador->getHorarios() as $horarioEscala) {
                        if ($horarioEscala->getDiaSemana() == $diaSemana + 1) {
                            $escala = $horarioEscala->getEscala();
                            break;
                        }
                    }

                    //busca os registros na catraca para o dia em questão
                    $batidaPonto = $this->entityManager->getRepository(\SigRH\Entity\BatidaPonto::class)->findOneBy(['colaboradorMatricula' => $colaborador, 'dataBatida' => $dataPesquisa]);
                    if ($batidaPonto && $escala) {
                        foreach ($batidaPonto->getHorarios() as $k => $horario) {
                            $intervaloE1 = $escala->getEntrada1()->diff($horario->getHoraBatida());
                            $intervaloS1 = $escala->getSaida1()->diff($horario->getHoraBatida());
                            
                            $intervaloMinutosE1 = ($intervaloE1->h * 60) + $intervaloE1->i;
                            $intervaloMinutosS1 = ($intervaloS1->h * 60) + $intervaloS1->i;
                            
                            $intervaloE2 = null;
                            $intervaloS2 = null;

                            
                            if(null != $escala->getEntrada2()) {
                                $intervaloE2 = $escala->getEntrada2()->diff($horario->getHoraBatida());
                                $intervaloS2 = $escala->getSaida2()->diff($horario->getHoraBatida());
                                $intervaloMinutosE2 = ($intervaloE2->h * 60) + $intervaloE2->i;
                                $intervaloMinutosS2 = ($intervaloS2->h * 60) + $intervaloS2->i;

                            }
                            
                            if ( $intervaloMinutosE1 < $intervaloMinutosS1) {
                                error_log("Entrada 1 - Registrou: ".$horario->getHoraBatida()->format("H:i"). " Escala: ".$escala->getEntrada1()->format("H:i"));
                                
                                if ($intervaloMinutosE1 > 5) {
                                    if ($intervaloE1->format("%R") == "-") {
                                        error_log("Entrada antecipada fora da tolerancia");
                                    } else {
                                        error_log("Entrada com atraso fora da tolerancia");
                                    }
                                }

//                                if ((int) $intervaloE1->format("%R%H%I") < -5 ) {
//                                    error_log("Adiantamento fora da tolerancia");
//                                }
//                                else if ((int) $intervaloE1->format("%R%H%I") > 5 ) {
//                                    error_log("Atraso fora da tolerancia");
//                                }
                                
                            } else {
                                error_log("Saida 1 - Registrou: ".$horario->getHoraBatida()->format("H:i"). " Escala: ".$escala->getSaida1()->format("H:i"));
                                
                                if ($intervaloMinutosS1 > 5) {
                                    if ($intervaloS1->format("%R") == "-") {
                                        error_log("Saida antecipada fora da tolerancia");
                                    } else {
                                        error_log("Saida atrasada fora da tolerancia");
                                    }
                                }
                            }
                        }
                    } else if ($escala != null && $batidaPonto == null) {
                       // $repo->incluir_ou_editar($colaborador, $dataPesquisa, null, 'Omissão de ponto - dia todo.', null);
                    }
                    $dataPesquisa->add(new \DateInterval('P1D'));
                }

//                    }//foreach colaboradores
                return $this->redirect()->toRoute('sig-rh/ocorrencia', ['action' => 'index']);
            }
        }
        return new ViewModel();
    }

}
