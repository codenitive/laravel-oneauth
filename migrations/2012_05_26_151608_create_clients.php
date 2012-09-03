<?php

class OneAuth_Create_Clients {
	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('oneauth_clients', function ($table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->string('provider', 50);
			$table->string('uid', 255);
			$table->text('access_token')->nullable(); # Changed to TEXT
			$table->string('secret', 255)->nullable();
			$table->text('refresh_token')->nullable(); # Changed to TEXT
			$table->integer('expires')->defaults(0)->nullable();

			$table->timestamps();
			//$table->index('access_token'); #can't/shouldn't index with type TEXT
			$table->index('user_id');
			$table->unique(array('provider', 'uid'));
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('oneauth_clients');
	}
}