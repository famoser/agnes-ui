# Agnes UI

This UI allows to use the deploy tool [famoser/agnes-ui](https://github.com/famoser/agnes) with ease.

- Create releases with release notes 
- Know exactly which version of the application is installed where
- Deploy new releases to environments
- Rollback the release if something is odd
- Copy shared data to another environment (effectively replicate another environment)

## Configuration

Configure your application with [famoser/agnes-ui](https://github.com/famoser/agnes).
Head over there to find out how to do this.

Set `TARGET_REPOSITORY` to the repository you want to deploy and have configured with agnes. 

(optional) Set `CONFIG_REPOSITORY` to a repository with additional agnes configuration for the repository you want to deploy.
If you have the agnes config in a subfolder, you can additionally set the `CONFIG_REPOSITORY_FOLDER` value.

Set `GITHUB_API_TOKEN` to a valid github token (generate a new one at https://github.com/settings/tokens).

## Screenshots

![Overview](assets/overview.png)
___
![Release](assets/release.png)
___
![Deploy](assets/deploy.png)
___
![Rollback](assets/rollback.png)
___
![Replicate](assets/replicate.png)
