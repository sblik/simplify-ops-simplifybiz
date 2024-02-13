<?php

/**
 * A repository for performing CRUD operations on the exhibitor onboarding form
 *
 * @method static ExhibitorOnboardingEntity | null get_one_for_user( $userId )
 * @method static ExhibitorOnboardingEntity[] | WP_Error get_all( $fieldId, $value, string $direction = 'ASC' )
 * @method static int | WP_Error add( ExhibitorOnboardingEntity $entity )
 */
class ExhibitorOnboardingRepository extends BS_BaseRepository {
	protected static $entityType = ExhibitorOnboardingEntity::class;
	protected static int $formId = FormIds::FORM_ID_26_REGISTER_AS_AN_EXHIBITOR;
}