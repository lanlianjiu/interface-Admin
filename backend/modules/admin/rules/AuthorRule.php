<?php
/**
 * @author: jiangyi
 * @date: 下午6:18 2018/7/20
 */

namespace backend\modules\admin\rules;

use yii\rbac\Rule;

class AuthorRule extends Rule
{
    public $name = 'isAuthor';

    public function execute($user, $item, $params)
    {
        return isset($params['post']) ? $params['post']->createdBy == $user : false;
    }
}
