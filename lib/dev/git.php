<pre>
<?

if (!$git_path) $git_path = "/root/git/bin/git";

if($allow_auto_updates){

    $github = json_decode($_POST['payload'],true);
    // get branch
    $temp = explode('/',$github['ref']);
    $branch = $temp[2];
    
    $codebase = $github['repository']['name'];
    $codebase_array = get_codebase_paths();
	$path = $codebase_array[ $codebase ]['path'];

	if ( $path ) {

        if ($branch && $branch != 'master') {
            // check if we have a folder for this branch
            $path = substr($path,0,-1) . '.' . $branch . '/';
            if ( !is_dir($path) ) $skip = true;
            // TODO: auto-deploy new branch dev site.. just do a git clone or whatever.
        }
        if (!$branch) $branch = 'master';

        if (!$skip) $command = "cd $path && (sudo $git_path pull origin $branch > /dev/null) 3>&1 1>&2 2>&3";

        $message .= "\n $command ";

		echo "$command\n";
		$t = exec($command);

		echo $t;
	}else{
		$message .= "\n '$codebase' is an invalid codebase.";
	}
}else{
	$message .= 'Auto updates are disabled, please set "$allow_auto_updates = true;" in your index.php file.';
}

echo $message;

?>
</pre>