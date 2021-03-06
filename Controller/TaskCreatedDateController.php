<?php
namespace Kanboard\Plugin\TaskCreatedDate\Controller;
use Kanboard\Controller\BaseController;
use Kanboard\Plugin\TaskCreatedDate\Model\TaskCreatedDateSettingsModel;
use Kanboard\Model\TaskModificationModel;
use Kanboard\Core\Controller\AccessForbiddenException;

/**
 * TaskCreatedDateController Controller. 
 * It controls everything related to main settings of the plugin.
 * @author   Olavo Alexandrino
 */
class TaskCreatedDateController extends BaseController
{
    /**
     * Returns the TaskCreatedDate settings
     * 
     * @author  Olavo Alexandrino
     * @return  array
     */    
    public function get()
    {
        $settings = $this->taskCreatedDateSettingsModel->get();
        return $settings;
    } 

    /**
     * Updates the date creation for a given task
     * 
     * @author  Olavo Alexandrino
     * @return  void
     */       
    public function update_task()
    {
        $aux = true;
        $message = "";
        $task = $this->getTask();
        $user = $this->getUser();
        
        if (!$this->userSession->isAdmin())
        {
            $aux = false;
            $message = 'Only administrators can update task creation dates.';
        }

        if (isset($task['owner_id']) && $user['id'] != $task['owner_id'] ) 
        {
            $aux = false;
            $message = 'You are not allowed to update tasks assigned to someone else.';
        }

        $values = $this->request->getValues();
        
        $values['id'] = $task['id'];
        $values['project_id'] = $task['project_id'];

        if (isset($values["date_creation"]) && !empty($values["date_creation"]))
        {
            $date = \DateTime::createFromFormat('d/m/Y H:i', $values["date_creation"]);
            $values["date_creation"] = $date->getTimestamp();
        }
        else
        {
            $aux = false;
            $message = 'You must provide a valid date.';
        }

        if ($task['date_due'] > 0 && $values["date_creation"] >= $task['date_due'] )
        {
            $aux = false;
            $message = 'The provided date must be earlier than the task due date.';   
        }

        if ($task['date_started'] > 0 &&  $values["date_creation"] >= $task['date_started'] )
        {
            $aux = false;
            $message = 'The provided date must be earlier than the task started date.';   
        }        

        if ($task['date_completed'] > 0 &&  $values["date_creation"] >= $task['date_completed'] )
        {
            $aux = false;
            $message = 'The provided date must be earlier than the task completed date.'; 
        }                
        
        if ($task['date_moved'] > 0 &&  $values["date_creation"] >= $task['date_moved'] )
        {
            $aux = false;
            $message = 'The provided date must be earlier than the last date of movement of the task.'; 
        }            

        if (!$aux)
        {
            $this->flash->failure(t($message));
            return $this->response->redirect($this->helper->url->to('TaskCreatedDateController', 'creationdate', array('task_id' => $task["id"] ,'project_id' => $task["project_id"] ,'plugin' => 'TaskCreatedDate')), true);
        }
        else
        {
            if ($this->taskModificationModel->update($values)) {
                $this->flash->success(t('Task updated successfully.'));
                return $this->response->redirect($this->helper->url->to('TaskCreatedDateController', 'creationdate', array('task_id' => $task["id"] ,'project_id' => $task["project_id"] ,'plugin' => 'TaskCreatedDate')), true);
            } else {
                $this->flash->failure(t('Unable to update your task.'));
                return $this->response->redirect($this->helper->url->to('TaskCreatedDateController', 'creationdate', array('task_id' => $task["id"] ,'project_id' => $task["project_id"] ,'plugin' => 'TaskCreatedDate')), true);
            }   
        }
    }

    /**
     * Updates the general settings
     * 
     * @author  Olavo Alexandrino
     * @return  void
     */       
    public function update_settings()
    {
        $user = $this->getUser();
        $values = $this->request->getValues();
        $values["user_id"] = $user["id"];
        $settings = $this->taskCreatedDateSettingsModel->get();
        $aux = false;

        if (is_array($settings)) 
        {
            if ($this->taskCreatedDateSettingsModel->update($values)) 
            {
                $aux = true;
            }
        } 
        else 
        {
            if ($this->taskCreatedDateSettingsModel->insert($values)) 
            {
                $aux = true;
            }
        }

        if ($aux) 
        {
            $this->flash->success(t('Settings has been updated successfully.'));
            return $this->response->redirect($this->helper->url->to('TaskCreatedDateController', 'settings', array('plugin' => 'TaskCreatedDate')), true);
        } 
        else 
        {
            $this->flash->failure(t('Unable to update.'));
        }
    }

    /**
     * Shows the form view to update the creation date of the given task
     * 
     * @author  Olavo Alexandrino
     * @return  void
     */     
    public function creationdate()
    {
        $settings = $this->taskCreatedDateController->get();
        $user = $this->getUser();
        $project = $this->getProject();   
        $task = $this->getTask();

        if (is_array($settings))
        {
            if($settings['enabled'] == 1)
            {
                $this->response->html($this->taskCreatedDateLayoutHelper->show('TaskCreatedDate:task/creationdate', 
                array(
                    'user' => $user,
                    'project' => $project,  
                    'task' => $task,            
                    'title' => t('TaskCreatedDate  settings'),
                )));  
            }
            else
            {
                throw new AccessForbiddenException(t('Plugin is not enabled.'));                 
            }
        }
    }

  /**
     * Shows a warning message in spite of updating the field
     * 
     * @author  Olavo Alexandrino
     * @throws \Kanboard\Core\Controller\AccessForbiddenException
     * @return  void
     */     
    public function warning()
    {
        $user = $this->getUser();
        $project = $this->getProject();
        $task = $this->getTask();        
        $settings = $this->taskCreatedDateController->get();
        
        if (is_array($settings))
        {
            if($settings['enabled'] == 1)
            {
                $this->response->html($this->taskCreatedDateLayoutHelper->show('TaskCreatedDate:task/warning', 
                array(
                    'user' => $user,
                    'project' => $project, 
                    'task'  => $task,
                    'title' => t('TaskCreatedDate  settings'),
                ))); 
            }
            else
            {
                throw new AccessForbiddenException(t('Plugin is not enabled.'));                 
            }
        }        
    }    

    /**
     * Shows the general settings form view
     * 
     * @author  Olavo Alexandrino
     * @return  void
     */      
    public function settings() 
    {
        $general_settings = $this->taskCreatedDateSettingsModel->get();
        $user = null;
        if (is_array($general_settings))
        {
            $user = $this->userModel->getById( $general_settings['user_id']);
            $general_settings = array(
                'enabled' => $general_settings['enabled'],
            );            
        }
        else 
        {
            $general_settings = array(
                'enabled' => "0",
            );   
        }
  
        $this->response->html($this->helper->layout->config('TaskCreatedDate:config/settings', 
        array(
            'title' => t('TaskCreatedDate  settings'),
            'user' => $user,
            'general_settings' => $general_settings,
        )));        
    }
}