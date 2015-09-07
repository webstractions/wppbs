<?php
/**
*  Settings Configuration
*/

	$sections = array();

	$sections[] = array(
		'id'       => 'general',
		'title'    => __( 'General', 'wppbs' ),
		'desc'     => 'Some fine tuning controls for this plugin.',
		'fields'   => array(
			array(
				'id'   => 'extra-stopwords',
				'label' => __( 'Extra stopwords', 'wppbs' ),
				'type' => 'textarea',
				'desc' => __( "Comma separated list of stopwords to refine your search (ie: free, sell, buy, etc.).", 'wppbs' )
			),
			array(
				'id'      => 'stopword-whitelists',
				'label'   => __( 'Stopword whitelists', 'wppbs' ),
				'type'    => 'checkbox',
				'options' => array(
					'en' => __( 'English', 'wppbs' ), 
					'fr' => __( 'French', 'wppbs' ), 
					'es' => __( 'Spanish', 'wppbs' ), 
				),
			),
			array(
				'id'      => 'stopword-level',
				'label'   => __( 'Stopword level', 'wppbs' ),
				'type'    => 'radio',
				'options' => array( 
					'fine'   =>__( 'Fine', 'wppbs' ),
					'medium' => __( 'Medium', 'wppbs' ), 
					'coarse' => __( 'Coarse', 'wppbs' ), 
				),
			),
		)
	);

	$sections[] = array(
		'id'       => 'youtube',
		'title'    => __( 'YouTube Settings', 'wppbs' ),
		'desc'     => __( "Uses version 3 of the YouTube Data API. Requires a developers key which can be obtained via the <a href='https://developers.google.com/youtube/v3/getting-started' target='_blank'>Google YouTube Developer Website</a>.", 'wppbs' ),
   		'fields'   => array(
   			array(
   				'id'    => 'yt_server_api_key',
   				'label' => __( 'Server API key' ),
   				'type'  => 'text',
   				'size'  => '39',
   			),
			array(
				'id'    => 'yt_max_results',
				'label' => __( 'Max results', 'wppbs' ),
				'type'  => 'number',
				'default' => '3', 
				'desc'  => __( 'Maximum number of results to return.', 'wppbs' )
			),
			array(
				'id'      => 'yt_max_keywords',
				'label'   => __( 'Max keywords', 'wppbs' ),
				'type'    => 'number',
				'default' => '5', 
				'desc'    => __( 'Maximum number of keywords to use in search.', 'wppbs' )
			),
			array(
				'id'      => 'yt_safe_search',
				'label'   => __( 'Safe search', 'wppbs' ),
				'type'    => 'select',
				'options' => array( 
					__( 'None', 'wppbs' ), 
					__( 'Moderate', 'wppbs' ), 
					__( 'Strict', 'wppbs' ), 
				),
				'default' => 0,  // default set for moderate
				'desc'    => __( 'Safe Search filters content based on this setting.', 'wppbs' )
			),
			array(
				'id'      => 'yt_order_by',
				'label'   => __( 'Ordered by', 'wppbs' ),
				'type'    => 'select',
				'options' => array( 
					__( 'Relevance', 'wppbs' ), 
					__( 'View Count', 'wppbs' ), 
					__( 'Updated', 'wppbs' ), 
				),
				'default' => 0,  // default set for relevance
				'desc'    => __( 'Choose the order of search results.', 'wppbs' )
			),
		)
	);

	$sections[] = array(
		'id'       => 'twitter',
		'title'    => __( 'Twitter Settings', 'wppbs' ),
		'desc'     => __( 'Uses the Twitter REST API. Requires a developers API key which can be obtained within the <a href="https://apps.twitter.com/">Twitter Application Management</a> console.', 'wppbs' ),
   		'fields'   => array(
   			array(
   				'id'    => 'tw_consumer_key',
   				'label' => __( 'OAuth Consumer Key' ),
   				'type'  => 'text',
   				'size'  => '64',
   			),
   			array(
   				'id'    => 'tw_consumer_secret',
   				'label' => __( 'OAuth Consumer Secret' ),
   				'type'  => 'text',
   				'size'  => '64',
   			),
   			array(
   				'id'    => 'tw_access_token',
   				'label' => __( 'OAuth Access Token' ),
   				'type'  => 'text',
   				'size'  => '64',
   			),
   			array(
   				'id'    => 'tw_access_secret',
   				'label' => __( 'OAuth Access Secret' ),
   				'type'  => 'text',
   				'size'  => '64',
   			),
			array(
				'id'    => 'tw_max_results',
				'label' => __( 'Max results', 'wppbs' ),
				'type'  => 'number',
				'default' => '5', 
				'desc'  => __( 'Maximum number of results to return.', 'wppbs' )
			),
			array(
				'id'      => 'tw_max_keywords',
				'label'   => __( 'Max keywords', 'wppbs' ),
				'type'    => 'number',
				'default' => '5', 
				'desc'    => __( 'Maximum number of keywords to use in search.', 'wppbs' )
			),
		)
	);