<?php

namespace wdmg\news\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use wdmg\news\models\News;

/**
 * NewsSearch represents the model behind the search form of `wdmg\news\models\News`.
 */
class NewsSearch extends News
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'in_sitemap', 'in_rss', 'in_turbo', 'in_amp'], 'integer'],
            [['name', 'alias', 'excerpt', 'title', 'description', 'keywords', 'status'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = News::find();

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'alias', $this->alias])
            ->andFilterWhere(['like', 'excerpt', $this->excerpt])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'keywords', $this->keywords]);

        if ($this->in_sitemap !== "*")
            $query->andFilterWhere(['like', 'in_sitemap', $this->in_sitemap]);

        if ($this->in_rss !== "*")
            $query->andFilterWhere(['like', 'in_rss', $this->in_rss]);

        if ($this->in_turbo !== "*")
            $query->andFilterWhere(['like', 'in_turbo', $this->in_turbo]);

        if ($this->in_amp !== "*")
            $query->andFilterWhere(['like', 'in_amp', $this->in_amp]);

        if ($this->status !== "*")
            $query->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }

}
