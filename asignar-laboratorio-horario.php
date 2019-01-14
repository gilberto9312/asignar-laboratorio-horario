<?php

	//////////////////////////////////////////////////////////////////////////////
	/*@author: Gilberto Asuaje <@gilberto9312, asuajegilberto@gmail.com>        //
	/*@Version: 1.1                                                             //
	/*@Descripcion: retorna array()                                             //
	/*Con el laboratorio y horario disponible                                   //
	/*////////////////////////////////////////////////////////////////////////////

    private function devuelveLaboratorioValido($idCanton, $periodo, $repositoryPost, $repositoryLab)
    {
        //$canton = 1;

        $em = $this->getDoctrine()->getManager();
        $periodo = $this->get('App\Controller\ServicioGeneralController')->consultaPeriodoActivo();
        $repository = $em->getRepository('App\Entity\TcertLaboratorio');
        $query = $repository->createQueryBuilder('tcl')
                                        ->innerJoin('tcl.facultad', 'tcf')
                                        ->innerJoin('tcf.universidad', 'tcu')
                                        ->innerJoin('tcu.provincia', 'tcp')
                                        ->innerJoin('tcu.canton', 'tcc')
                                        ->where('tcc.id = :id')->setParameter('id', $idCanton)
                                        ->andWhere('tcl.status = true')
                                        ->getQuery();
        $laboratorios = $query->getResult();
        //dump($laboratorios);die;
        $infoLab = array();
        foreach($laboratorios as $laboratorio)
        {
            foreach($laboratorio->getTcertHorariosid() as $horario)
            {
                if ($horario->getIdPeriodo() == $periodo && $horario->getStatus() == true)
                {
                    $postulaciones = $repositoryPost->findBy(array('laboratorio'=>$laboratorio, 'horario'=>$horario));
                    //$capacidadLaboratorio = $laboratorio->getCapacidad() - round(($laboratorio->getCapacidad() * 0.10), PHP_ROUND_HALF_DOWN);
                    $capacidadLaboratorio = $laboratorio->getCapacidad();
                    $totalPostulantesLaboratorioHorario = count($postulaciones);
                    
                    if ($totalPostulantesLaboratorioHorario < $capacidadLaboratorio)
                    {
                        $infoLab['status'] = true;
                        $infoLab['idLaboratorio'] = $laboratorio->getId();
                        $infoLab['idHorario'] = $horario->getId();
                        return $infoLab;
                    }
                }
            }
        }
        
        $infoLab['status'] = false;
        
        return $infoLab;
    }
?>
