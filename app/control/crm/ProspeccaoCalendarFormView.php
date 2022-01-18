<?php
/**
 * ProspeccaoCalendarForm Form
 * @author  <your name here>
 */
class ProspeccaoCalendarFormView extends TPage
{
    private $fc;

    /**
     * Page constructor
     */
    public function __construct($param = null)
    {
        parent::__construct();

        $this->fc = new TFullCalendar(date('Y-m-d'), 'month');
        $this->fc->enableDays([1,2,3,4,5]);
        $this->fc->setReloadAction(new TAction(array($this, 'getEvents'), $param));
        $this->fc->setDayClickAction(new TAction(array('ProspeccaoCalendarForm', 'onStartEdit')));
        $this->fc->setEventClickAction(new TAction(array('ProspeccaoCalendarForm', 'onEdit')));
        $this->fc->setEventUpdateAction(new TAction(array('ProspeccaoCalendarForm', 'onUpdateEvent')));
        $this->fc->setCurrentView('agendaWeek');
        $this->fc->setTimeRange('07:00', '19:00');
        $this->fc->enablePopover('Informações', "Título: {titulo} 
Cliente: {cliente->nome} ");

        parent::add( $this->fc );
    }

    /**
     * Output events as an json
     */
    public static function getEvents($param=NULL)
    {
        $return = array();
        try
        {
            TTransaction::open('base_banco');

            $criteria = new TCriteria(); 

            $criteria->add(new TFilter('horario_inicial', '<=', $param['end'].' 23:59:59'));
            $criteria->add(new TFilter('horario_final', '>=', $param['start'].' 00:00:00'));

            $filterVar = TSession::getValue("userid");
            $criteria->add(new TFilter('vendedor_id', 'in', "(SELECT id FROM pessoa WHERE system_user_id = '{$filterVar}')")); 
            $filterVar = TSession::getValue("userunitid");
            $criteria->add(new TFilter('system_unit_id', '=', $filterVar)); 

            $events = Prospeccao::getObjects($criteria);

            if ($events)
            {
                foreach ($events as $event)
                {
                    $event_array = $event->toArray();
                    $event_array['start'] = str_replace( ' ', 'T', $event_array['horario_inicial']);
                    $event_array['end'] = str_replace( ' ', 'T', $event_array['horario_final']);
                    $event_array['id'] = $event->id;
                    $event_array['color'] = $event->render("{cor}");
                    $event_array['title'] = TFullCalendar::renderPopover($event->render("{titulo}"), $event->render("Informações"), $event->render("Título: {titulo} 
Cliente: {cliente->nome} "));

                    $return[] = $event_array;
                }
            }
            TTransaction::close();
            echo json_encode($return);
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }

    /**
     * Reconfigure the callendar
     */
    public function onReload($param = null)
    {
        if (isset($param['view']))
        {
            $this->fc->setCurrentView($param['view']);
        }

        if (isset($param['date']))
        {
            $this->fc->setCurrentDate($param['date']);
        }
    }

}

