<?php

/*
 * @author     M2E Pro Developers Team
 * @copyright  M2E LTD
 * @license    Commercial use is forbidden
 */

namespace Ess\M2ePro\Model\ResourceModel\Walmart\Template;

class Category extends \Ess\M2ePro\Model\ResourceModel\ActiveRecord\AbstractModel
{
    const SYNCH_REASON = 'categoryTemplate';

    //########################################

    public function _construct()
    {
        $this->_init('m2epro_walmart_template_category', 'id');
    }

    //########################################

    public function setSynchStatusNeed($newData, $oldData, $listingsProducts)
    {
        if (empty($listingsProducts)) {
            return;
        }

        $listingsProductsIds = array();
        foreach ($listingsProducts as $listingProduct) {
            $listingsProductsIds[] = $listingProduct['id'];
        }

        if (!$this->isDifferent($newData,$oldData)) {
            return;
        }

        $templates = array(self::SYNCH_REASON);

        $lpTable = $this->activeRecordFactory->getObject('Listing\Product')->getResource()->getMainTable();

        $this->getConnection()->update(
            $lpTable,
            array(
                'synch_status' => \Ess\M2ePro\Model\Listing\Product::SYNCH_STATUS_NEED,
                'synch_reasons' => new \Zend_Db_Expr(
                    "IF(synch_reasons IS NULL,
                        '".implode(',',$templates)."',
                        CONCAT(synch_reasons,'".','.implode(',',$templates)."')
                    )"
                )
            ),
            array('id IN ('.implode(',', $listingsProductsIds).')')
        );
    }

    // ---------------------------------------

    public function isDifferent($newData, $oldData)
    {
        $ignoreFields = array(
            $this->getIdFieldName(),
            'id', 'title','create_date', 'update_date',
        );

        foreach ($ignoreFields as $ignoreField) {
            unset($newData[$ignoreField],$oldData[$ignoreField]);
        }

        $specificsNewData = isset($newData['specifics']) ? $newData['specifics'] : array();
        $specificsOldData = isset($oldData['specifics']) ? $oldData['specifics'] : array();
        unset($newData['specifics'], $oldData['specifics']);

        $ignoreFields = array('id', 'template_category_id', 'update_date', 'create_date');
        foreach ($specificsNewData as $key => $newInfo) {
            foreach ($ignoreFields as $ignoreField) {
                unset($specificsNewData[$key][$ignoreField]);
            }
        }
        foreach ($specificsOldData as $key => $newInfo) {
            foreach ($ignoreFields as $ignoreField) {
                unset($specificsOldData[$key][$ignoreField]);
            }
        }

        array_walk($specificsNewData, 'ksort');
        array_walk($specificsOldData, 'ksort');

        $encodedSpecificsNewData = $this->getHelper('Data')->jsonEncode($specificsNewData);
        $encodedSpecificsOldData = $this->getHelper('Data')->jsonEncode($specificsOldData);

        return md5($encodedSpecificsNewData) !== md5($encodedSpecificsOldData) ||
               count(array_diff_assoc($newData, $oldData));
    }

    //########################################
}