<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Laravel\Passport\RefreshTokenRepository;
use Laravel\Passport\Token;
use Laravel\Passport\TokenRepository;

use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class RevokeAccessTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:revoke-access-tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Revoke a user's access tokens";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $id = text(
            label: "What is the user's id?",
            required: true
        );

        $clearRefreshTokens = select(
            label: "Revoke the user's refresh tokens?",
            options: [
                true => 'Yes',
                false => 'No',
            ]
        );

        $user = User::findOrFail($id);
        $tokenRepository = app(TokenRepository::class);
        $refreshTokenRepository = app(RefreshTokenRepository::class);

        $user->tokens->each(function (Token $item) use (
            $tokenRepository,
            $refreshTokenRepository,
            $clearRefreshTokens
        ) {
            // Revoke an access token
            $tokenRepository->revokeAccessToken($item->id);
            $this->info('Access Token revoked: '.$item->id);

            if ($clearRefreshTokens) {
                // Revoke all the token's refresh tokens
                $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($item->id);
                $this->line('Refresh token revoked.');
                $this->newLine();
            }
        });
    }
}
