<?php
/**
 * Este archivo contiene los datos de un fixture para los casos de prueba.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright		Copyright 2007-2008, Pragmatia de RPB S.A.
 * @link			http://www.pragmatia.com
 * @package			pragtico
 * @subpackage		app.tests.fixtures
 * @since			Pragtico v 1.0.0
 * @version			$Revision: 54 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2008-10-23 23:14:28 -0300 (Thu, 23 Oct 2008) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase para un fixture para un caso de prueba.
 *
 * @package app.tests
 * @subpackage app.tests.fixtures
 */
class VariableFixture extends CakeTestFixture {


/**
 * El nombre de este Fixture.
 *
 * @var array
 * @access public
 */
    var $name = 'Variable';


/**
 * La definicion de la tabla.
 *
 * @var array
 * @access public
 */
    var $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'primary'),
        'nombre' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '50', 'key' => 'unique'),
        'formula' => array('type' => 'text', 'null' => false, 'default' => ''),
        'formato' => array('null' => false, 'default' => 'Minuscula', 'length' => '14'),
        'descripcion' => array('type' => 'text', 'null' => false, 'default' => ''),
        'ejemplo' => array('type' => 'text', 'null' => false, 'default' => ''),
        'created' => array('type' => 'datetime', 'null' => false),
        'modified' => array('type' => 'datetime', 'null' => false),
        'user_id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'index'),
        'role_id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'index'),
        'group_id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'index'),
        'permissions' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'index'),
    );


/**
 * Los registros.
 *
 * @var array
 * @access public
 */
    var $records = array(
        array(
            'id' => '1',
            'nombre' => '#dias_antiguedad',
            'formula' => '',
            'formato' => 'Minuscula',
            'descripcion' => 'Se refiere a los dias transcurridos desde el ingreso del trabajador hasta la fecha de cierre de la liquidacion',
            'ejemplo' => '',
            'created' => '2007-11-19 12:47:38',
            'modified' => '2008-04-07 07:14:59',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '2',
            'nombre' => '#meses_antiguedad',
            'formula' => '',
            'formato' => 'Minuscula',
            'descripcion' => 'Se refiere a los meses transcurridos desde el ingreso del trabajador hasta la fecha de liquidacion (redondea para abajo).',
            'ejemplo' => '',
            'created' => '2007-11-19 12:51:02',
            'modified' => '2007-11-19 13:17:39',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '3',
            'nombre' => '#anos_antiguedad',
            'formula' => '',
            'formato' => 'Minuscula',
            'descripcion' => 'Se refiere a los anos transcurridos desde la fecha de ingreso del trabajador hasta la fecha egreso o fecha de liquidacion(redondea para abajo).',
            'ejemplo' => '',
            'created' => '2007-11-19 12:51:28',
            'modified' => '2008-05-04 16:51:06',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '4',
            'nombre' => '#anos_antiguedad_al_31_12',
            'formula' => '',
            'formato' => 'Minuscula',
            'descripcion' => 'Se refiere a los anos transcurridos desde el ingreso del trabajador hasta el 31/12 del ano anterior a la liquidacion (redondea para abajo). En caso de que su fecha de ingreso sea posterior al 31/12 este valor sera 0.',
            'ejemplo' => '',
            'created' => '2007-11-19 12:54:10',
            'modified' => '2008-04-09 16:37:33',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '5',
            'nombre' => '#dias_antiguedad_al_31_12',
            'formula' => '',
            'formato' => 'Minuscula',
            'descripcion' => 'Se refiere a los dias transcurridos desde el ingreso del trabajador hasta el 31/12 del ano anterior a la liquidacion. En caso de que su fecha de ingreso sea posterior al 31/12 este valor sera 0.',
            'ejemplo' => '',
            'created' => '2007-11-19 12:54:47',
            'modified' => '2008-04-09 16:37:33',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '6',
            'nombre' => '#meses_antiguedad_al_31_12',
            'formula' => '',
            'formato' => 'Minuscula',
            'descripcion' => 'Se refiere a los meses transcurridos desde el ingreso del trabajador hasta el 31/12 del ano anterior a la liquidacion (redondea para abajo). En caso de que su fecha de ingreso sea posterior al 31/12 este valor sera 0.',
            'ejemplo' => '',
            'created' => '2007-11-19 12:55:14',
            'modified' => '2008-04-09 16:37:33',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '7',
            'nombre' => '#dia_ingreso',
            'formula' => '',
            'formato' => 'Minuscula',
            'descripcion' => 'Se refiere al dia de ingreso del trabajador.',
            'ejemplo' => 'Si el trabajador ingreso el 23/04/2001, esta variable contendra el valor 23.',
            'created' => '2007-11-19 12:59:34',
            'modified' => '2007-11-19 17:11:29',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '8',
            'nombre' => '#mes_ingreso',
            'formula' => '',
            'formato' => 'Minuscula',
            'descripcion' => 'Se refiere al mes de ingreso del trabajador.',
            'ejemplo' => 'Si el trabajador ingreso el 23/04/2001, esta variable contendra el valor 04.',
            'created' => '2007-11-19 13:03:16',
            'modified' => '2008-04-07 12:37:24',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '9',
            'nombre' => '#ano_ingreso',
            'formula' => '',
            'formato' => 'Minuscula',
            'descripcion' => 'Se refiere al ano de ingreso del trabajador.',
            'ejemplo' => 'Si el trabajador ingreso el 23/04/2001, esta variable contendra el valor 2001.',
            'created' => '2007-11-19 13:03:46',
            'modified' => '2007-11-19 17:11:20',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '10',
            'nombre' => '#fecha_ingreso',
            'formula' => '[Relacion][ingreso]',
            'formato' => 'Minuscula',
            'descripcion' => 'Se refiere a la fecha de inicio de la relacion laboral.',
            'ejemplo' => '',
            'created' => '2007-11-19 13:32:20',
            'modified' => '2007-11-19 17:11:38',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '11',
            'nombre' => '#fecha_actual',
            'formula' => '',
            'formato' => 'Minuscula',
            'descripcion' => 'Se refiere a la fecha de hoy.',
            'ejemplo' => '',
            'created' => '2007-11-19 14:01:20',
            'modified' => '2007-11-19 14:01:20',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '14',
            'nombre' => '#jornada',
            'formula' => '[Relacion][horas]',
            'formato' => 'Minuscula',
            'descripcion' => 'Se refiere a las horas por dia que debe trabajar el trabajador.',
            'ejemplo' => 'jornada de 6 horas por dia.',
            'created' => '2007-11-20 13:07:37',
            'modified' => '2007-11-20 13:07:37',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '15',
            'nombre' => '#ausencias_justificadas',
            'formula' => '',
            'formato' => 'Minuscula',
            'descripcion' => 'Se refiere a las faltas que tuvo el trabajador para el periodo a liquidar que hayan sido debidamente justificadas.',
            'ejemplo' => '',
            'created' => '2007-11-20 18:55:49',
            'modified' => '2007-12-13 14:35:45',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '16',
            'nombre' => '#presentismo_dias_tolerancia',
            'formula' => '[Convenio][presentismo_tolerancia]',
            'formato' => 'Minuscula',
            'descripcion' => 'Se refiere a los dias de faltas toleradas que admite el convenio colectivo para que deba pagarse el presentismo.',
            'ejemplo' => '',
            'created' => '2007-11-20 19:41:21',
            'modified' => '2007-11-20 19:47:26',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '17',
            'nombre' => '#presentismo_importe',
            'formula' => '[Convenio][presentismo_importe]',
            'formato' => 'Minuscula',
            'descripcion' => 'Se refiere al importe a pagar por el presentismo segun el convenio colectivo.',
            'ejemplo' => '',
            'created' => '2007-11-20 19:46:40',
            'modified' => '2007-11-20 19:46:40',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '18',
            'nombre' => '#presentismo_porcentaje',
            'formula' => '[Convenio][presentismo_porcentaje]',
            'formato' => 'Minuscula',
            'descripcion' => 'Se refiere al porcentaje a calcular para el pago del presentismo segun el convenio colectivo.',
            'ejemplo' => '',
            'created' => '2007-11-20 19:48:27',
            'modified' => '2007-11-20 19:48:27',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '19',
            'nombre' => '#valor_segun_convenio',
            'formula' => '[ConveniosCategoria][costo]',
            'formato' => 'Minuscula',
            'descripcion' => 'Se refiere al costo (de la hora o el sueldo mensual segun la categoria del convenio colectivo asociado a la relacion)',
            'ejemplo' => '',
            'created' => '2007-12-10 01:10:02',
            'modified' => '2008-04-07 07:04:23',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '20',
            'nombre' => '#valor_segun_relacion',
            'formula' => '[Relacion][basico]',
            'formato' => 'Minuscula',
            'descripcion' => 'Se refiere al costo (de la hora o el sueldo mensual) especificado en la relacion laboral.',
            'ejemplo' => '',
            'created' => '2007-12-10 01:11:35',
            'modified' => '2008-04-07 07:04:23',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '21',
            'nombre' => '#mes_liquidacion',
            'formula' => '',
            'formato' => 'Minuscula',
            'descripcion' => 'Se refiere al mes del cual se esta realizando la liquidacion.',
            'ejemplo' => '',
            'created' => '2007-12-10 01:25:03',
            'modified' => '2007-12-10 01:25:03',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '22',
            'nombre' => '#ano_liquidacion',
            'formula' => '',
            'formato' => 'Minuscula',
            'descripcion' => 'Se refiere al ano del cual se esta realizando la liquidacion.',
            'ejemplo' => '',
            'created' => '2007-12-10 01:27:15',
            'modified' => '2007-12-13 15:19:58',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '23',
            'nombre' => '#horas',
            'formula' => '',
            'formato' => 'Minuscula',
            'descripcion' => 'Se refiere a las horas efectivamente trabajadas en el periodo a liquidar.',
            'ejemplo' => '',
            'created' => '2007-12-12 16:06:12',
            'modified' => '2008-04-16 07:12:59',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '24',
            'nombre' => '#horas_extra_50',
            'formula' => '',
            'formato' => 'Minuscula',
            'descripcion' => 'Se refiere a las horas extras al 50% trabajadas durante el periodo.',
            'ejemplo' => '',
            'created' => '2007-12-12 16:06:43',
            'modified' => '2008-04-16 07:12:59',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '25',
            'nombre' => '#horas_extra_100',
            'formula' => '',
            'formato' => 'Minuscula',
            'descripcion' => 'Se refiere a las horas extras al 100% trabajadas durante el periodo.',
            'ejemplo' => '',
            'created' => '2007-12-12 16:06:56',
            'modified' => '2008-04-16 07:12:59',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '26',
            'nombre' => '#horas_ajuste',
            'formula' => '',
            'formato' => 'Minuscula',
            'descripcion' => 'Se refiere a las horas normales de ajuste.',
            'ejemplo' => '',
            'created' => '2007-12-12 16:07:24',
            'modified' => '2008-04-16 07:12:59',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '27',
            'nombre' => '#periodo_liquidacion',
            'formula' => '',
            'formato' => 'Minuscula',
            'descripcion' => 'Se refiere al periodo del cual se esta realizando la liquidacion.',
            'ejemplo' => '1Q, 2Q o M',
            'created' => '2007-12-12 17:26:01',
            'modified' => '2008-04-22 09:35:48',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '28',
            'nombre' => '#ausencias_injustificadas',
            'formula' => '',
            'formato' => 'Minuscula',
            'descripcion' => 'Se refiere a las faltas que tuvo el trabajador para el periodo a liquidar que no hayan sido debidamente justificadas.',
            'ejemplo' => '',
            'created' => '2007-12-13 14:36:01',
            'modified' => '2007-12-13 14:36:01',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '29',
            'nombre' => '#valor',
            'formula' => '=if(#valor_segun_relacion > 0, #valor_segun_relacion, #valor_segun_convenio)',
            'formato' => 'Minuscula',
            'descripcion' => 'Este valor puede referirse al valor una hora (si se trata de un trabajador jornalizado) o a el importe mensual, derivado del convenio colectivo o de la relacion, si se trata de un trabajador mensualizado.',
            'ejemplo' => '',
            'created' => '2007-12-13 18:33:09',
            'modified' => '2008-04-07 13:15:25',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '30',
            'nombre' => '#contratacion',
            'formula' => '[ConveniosCategoria][jornada]',
            'formato' => 'Minuscula',
            'descripcion' => 'Se refiere al tipo de contratacion (mensual o por hora)',
            'ejemplo' => '',
            'created' => '2007-12-13 19:04:49',
            'modified' => '2008-04-01 03:54:28',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '31',
            'nombre' => '#fecha_egreso',
            'formula' => '[Relacion][egreso]',
            'formato' => 'Minuscula',
            'descripcion' => 'Se refiere a la fecha de finalizacion de la relacion laboral.',
            'ejemplo' => '',
            'created' => '2008-04-07 07:26:10',
            'modified' => '2008-04-07 07:26:10',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '32',
            'nombre' => '#dias_corridos_periodo',
            'formula' => '',
            'formato' => 'Minuscula',
            'descripcion' => 'Son los dias transcurridos en el periodo desde el primer dia del periodo a liquidar o la fecha de inicio de la relacion si esta fuese posterior al primer dia del periodo, hasta el ultimo dia del periodo a liquidar o la fecha de egreso si esta fuese anterior.',
            'ejemplo' => 'Si la fecha de ingreso es el 10/04/2008 y el periodo a liquidar abril del 2008, la variable contendra el valor 20.',
            'created' => '2008-04-07 08:10:15',
            'modified' => '2008-04-07 12:40:53',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '33',
            'nombre' => '#dias_vacaciones',
            'formula' => '',
            'formato' => 'Minuscula',
            'descripcion' => 'Dias efectivos de vacaciones en el periodo a liquidar',
            'ejemplo' => '',
            'created' => '2008-04-07 08:13:43',
            'modified' => '2008-04-07 08:13:43',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '34',
            'nombre' => '#dias_licencia',
            'formula' => '',
            'formato' => 'Minuscula',
            'descripcion' => 'Cantidad de dias no laborados en el periodo a causa de una licencia sin importar si se trata de licencia sin goce de haberes o por maternidad, casamiento, etc.',
            'ejemplo' => '',
            'created' => '2008-04-07 08:20:47',
            'modified' => '2008-04-07 12:42:16',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '35',
            'nombre' => '#dias_trabajados',
            'formula' => '=#dias_corridos_periodo - #dias_vacaciones - #dias_licencia - #ausencias_injustificadas',
            'formato' => 'Minuscula',
            'descripcion' => 'Son la cantidad de dias trabajados en el periodo a liquidar, incluyendo los dias de enfermedad.',
            'ejemplo' => '',
            'created' => '2008-04-07 08:30:37',
            'modified' => '2008-04-07 12:43:42',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '36',
            'nombre' => '#dia_egreso',
            'formula' => '',
            'formato' => 'Minuscula',
            'descripcion' => 'Se refiere al dia de finalizacion de la relacion laboral.',
            'ejemplo' => 'Si la relacion laboral finalizo el 28/07/2004, esta variable contendra el valor 28.',
            'created' => '2008-04-07 09:21:18',
            'modified' => '2008-04-07 09:21:18',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '37',
            'nombre' => '#ano_egreso',
            'formula' => '',
            'formato' => 'Minuscula',
            'descripcion' => 'Se refiere al ano de finalizacion de la relacion laboral.',
            'ejemplo' => 'Si la relacion laboral finalizo el 28/07/2004, esta variable contendra el valor 2004.',
            'created' => '2008-04-07 11:55:07',
            'modified' => '2008-04-07 12:36:54',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '38',
            'nombre' => '#mes_egreso',
            'formula' => '',
            'formato' => 'Minuscula',
            'descripcion' => 'Se refiere al mes de finalizacion de la relacion laboral.',
            'ejemplo' => 'Si la relacion laboral finalizo el 28/07/2004, esta variable contendra el valor 07.',
            'created' => '2008-04-07 11:55:30',
            'modified' => '2008-04-07 12:36:54',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '39',
            'nombre' => '#dia_desde_liquidacion',
            'formula' => '',
            'formato' => 'Minuscula',
            'descripcion' => 'Toma solo el dia de la fecha que marca el inico de la liquidacion.',
            'ejemplo' => 'Si el periodo de liquidacion inicia el 16/04/2008, esta variable contendra el valor 16.',
            'created' => '2008-04-07 12:11:18',
            'modified' => '2008-04-09 16:24:49',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '40',
            'nombre' => '#dia_hasta_liquidacion',
            'formula' => '',
            'formato' => 'Minuscula',
            'descripcion' => 'Toma solo el dia de la fecha que marca el fin de la liquidacion.',
            'ejemplo' => 'Si el periodo de liquidacoin finaliza el 30/04/2008, esta variable contendra el valor 30.',
            'created' => '2008-04-07 12:11:38',
            'modified' => '2008-04-09 16:27:39',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '41',
            'nombre' => '#horas_ajuste_extra_100',
            'formula' => '',
            'formato' => 'Minuscula',
            'descripcion' => 'Se refiere a las horas extras al 100% de ajuste.',
            'ejemplo' => '',
            'created' => '2008-04-16 07:11:42',
            'modified' => '2008-04-16 07:12:59',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '42',
            'nombre' => '#horas_ajuste_extra_50',
            'formula' => '',
            'formato' => 'Minuscula',
            'descripcion' => 'Se refiere a las horas extras al 50% de ajuste.',
            'ejemplo' => '',
            'created' => '2008-04-16 07:12:06',
            'modified' => '2008-04-16 07:12:59',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '43',
            'nombre' => '#periodo_liquidacion_completo',
            'formula' => '',
            'formato' => 'Minuscula',
            'descripcion' => 'Se refiere al periodo del cual se esta realizando la liquidacion, en formato AAAAMM[1Q|2Q|M]',
            'ejemplo' => '200804M
2007112Q',
            'created' => '2008-04-21 12:59:44',
            'modified' => '2008-04-21 13:00:23',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '44',
            'nombre' => '#regimen_jubilatorio',
            'formula' => '[Trabajador][jubilacion]',
            'formato' => 'Minuscula',
            'descripcion' => 'Se refiere a la opcion del regimen jubilatorio escogido por el trabajador.',
            'ejemplo' => 'Capitalizacion o Reparto',
            'created' => '2008-04-27 22:57:59',
            'modified' => '2008-04-27 22:57:59',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '45',
            'nombre' => '#obra_social',
            'formula' => '[Trabajador][ObrasSocial][nombre]',
            'formato' => 'Mantener Valor',
            'descripcion' => 'Se refiere al nombre de la Obra Social escogida por el trabajador.',
            'ejemplo' => '',
            'created' => '2008-04-28 00:40:49',
            'modified' => '2008-08-05 12:29:04',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '46',
            'nombre' => '#fecha_desde_liquidacion',
            'formula' => '',
            'formato' => 'Minuscula',
            'descripcion' => 'Se refiere a la fecha que se tomara como inicio de la liquidacion.',
            'ejemplo' => '',
            'created' => '2008-04-28 10:12:01',
            'modified' => '2008-04-28 10:12:01',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '47',
            'nombre' => '#fecha_hasta_liquidacion',
            'formula' => '',
            'formato' => 'Minuscula',
            'descripcion' => 'Se refiere a la fecha que se tomara como final de la liquidacion.',
            'ejemplo' => '',
            'created' => '2008-04-28 10:12:17',
            'modified' => '2008-04-28 10:13:27',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '48',
            'nombre' => '#dias_licencia_enfermedad',
            'formula' => '',
            'formato' => 'Mantener Valor',
            'descripcion' => 'Cantidad de dias no laborados en el periodo a causa de una licencia por enfermedad.',
            'ejemplo' => '',
            'created' => '2008-04-29 11:49:49',
            'modified' => '2008-04-29 11:49:49',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '49',
            'nombre' => '#tipo_liquidacion',
            'formula' => '',
            'formato' => 'Minuscula',
            'descripcion' => 'Se refiere al tipo de liquidacion que se esta relaizando, puede ser liquidacion Normal, Sac, Vacaciones o Liquidacion Final',
            'ejemplo' => '',
            'created' => '2008-05-19 09:37:35',
            'modified' => '2008-05-19 09:37:35',
            'user_id' => '1',
            'role_id' => '15',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '50',
            'nombre' => '#smvm',
            'formula' => '=980',
            'formato' => 'Minuscula',
            'descripcion' => 'Monto del Salaio Minimo Vital y Movil',
            'ejemplo' => '',
            'created' => '2008-05-19 10:01:50',
            'modified' => '2008-05-19 10:01:50',
            'user_id' => '1',
            'role_id' => '15',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '51',
            'nombre' => '#horas_extra_nocturna_50',
            'formula' => '',
            'formato' => 'Minuscula',
            'descripcion' => 'Se refiere a las horas nocturnas extras al 50% trabajadas durante el periodo.',
            'ejemplo' => '',
            'created' => '2008-10-29 21:20:33',
            'modified' => '2008-10-29 21:20:33',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '496',
        ),
        array(
            'id' => '52',
            'nombre' => '#horas_extra_nocturna_100',
            'formula' => '',
            'formato' => 'Minuscula',
            'descripcion' => 'Se refiere a las horas nocturnas extras al 100% trabajadas durante el periodo.',
            'ejemplo' => '',
            'created' => '2008-10-29 21:20:53',
            'modified' => '2008-10-29 21:20:53',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '496',
        ),
        array(
            'id' => '53',
            'nombre' => '#horas_nocturna',
            'formula' => '',
            'formato' => 'Minuscula',
            'descripcion' => 'Se refiere a las horas nocturnas normales de ajuste.',
            'ejemplo' => '',
            'created' => '2008-10-29 21:22:12',
            'modified' => '2008-10-29 21:22:12',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '496',
        ),
        array(
            'id' => '54',
            'nombre' => '#horas_ajuste_extra_nocturna_50',
            'formula' => '',
            'formato' => 'Minuscula',
            'descripcion' => 'Se refiere a las horas nocturnas extras al 50% de ajuste.',
            'ejemplo' => '',
            'created' => '2008-10-29 21:25:00',
            'modified' => '2008-10-29 21:25:00',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '496',
        ),
        array(
            'id' => '55',
            'nombre' => '#horas_ajuste_extra_nocturna_100',
            'formula' => '',
            'formato' => 'Minuscula',
            'descripcion' => 'Se refiere a las horas nocturnas extras al 100% de ajuste.',
            'ejemplo' => '',
            'created' => '2008-10-29 21:25:34',
            'modified' => '2008-10-29 21:25:34',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '496',
        ),
        array(
            'id' => '56',
            'nombre' => '#horas_ajuste_nocturna',
            'formula' => '',
            'formato' => 'Minuscula',
            'descripcion' => 'Se refiere a las horas nocturnas normales de ajuste.',
            'ejemplo' => '',
            'created' => '2008-10-29 21:27:58',
            'modified' => '2008-10-29 21:27:58',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '496',
        )
    );
}

?>