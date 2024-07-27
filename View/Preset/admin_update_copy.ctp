<?php
    public function deleteSelect(){
if(!empty($this->data)) {
    foreach($this->data['Post']['box'] as $key => $value){

            $this->Post->delete($value);
    }
    $this->redirect(array('action' => 'index'));
}

}
?>