<?php

class Companyperformance_Model extends CC_Model
{
    public function getList($type, $requestdata = [])
    {
        $this->db->select('*');
        $this->db->from('company_performance_type');

        if(isset($requestdata['id'])) $this->db->where('id', $requestdata['id']);

        if ($type == 'count') {
            $result = $this->db->count_all_results();
        } else {
            $query = $this->db->get();

            if ($type == 'all') {
                $result = $query->result_array();
            } elseif ($type == 'row') {
                $result = $query->row_array();
            }

        }

        return $result;
    }

    public function action($data)
    {
        // print_r($data);die;
        $this->db->trans_begin();
        $userid   = $this->getUserID();
        $datetime = date('Y-m-d H:i:s');

        $point = $data['points'];

        foreach ($point as $k => $v) {
            $request = [
                'updated_at' => $datetime,
                'updated_by' => $userid,
                'points'     => $v,
            ];
            $this->db->update('company_performance_type', $request, ['id' => $k]);
        }

        foreach ($point as $pointkey => $pointvalue) {
            $request1 = [
                'created_at'        => $datetime,
                'admin_id'          => $userid,
                'new_points'        => $pointvalue,
                'system_ip'         => $_SERVER['REMOTE_ADDR'],
                'document_type'     => $pointkey
            ];
            $this->db->insert('company_performance_type_log', $request1);
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

}
